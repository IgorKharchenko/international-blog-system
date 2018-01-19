<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use app\models\User;
use app\models\Blog;
use app\models\Category;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\db\ActiveQuery;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     *
     * @return mixed
     */
    public function actionIndex($blog_id, $category = '')
    {
        $author = Blog::findAuthorOfBlog($blog_id);
        $blog = Blog::getBlogByAuthorId($author->id);
        $HasPrivilegies_Post = false;
        if ($blog->checkUDPrivilegies($blog)) {
            $HasPrivilegies_Post = true;
        }
        $model = new Post;
        if ($category == '') {
            $query = Post::find();
        } else {
            $query = Post::find()->where([
                'LIKE',
                'categories',
                $category,
            ]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount'      => $query->andWhere(['publish_status' => 'publish'])
                                       ->andWhere(['blog_id' => $blog_id])
                                       ->count(),
        ]);

        $posts = $model->findAllDisplayedPosts($pagination, $blog_id, $category);
        $category = new Category;

        return $this->render('index', [
            'blog'                => $blog,
            'posts'               => $posts,
            'author'              => $author,
            'HasPrivilegies_Post' => $HasPrivilegies_Post,
            'pagination'          => $pagination,
            'categories_all'      => $category->checkAllPostsCategories($posts),
        ]);
    }

    /**
     * Displays a single Post model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id, $blog_id)
    {
        $model = $this->findModel($id);
        $blog = Blog::getBlogByAuthorId($model->author_id);
        $post_author = Blog::findAuthorOfBlog($blog_id);
        $category = new Category;

        // find ALL comments with [comment.post_id == post.id]
        $comments_model = new Comment;
        $comments_pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount'      => $model->comments_count,
        ]);
        $comments = $comments_model->findPostComments($id, $comments_pagination); # 1 step
        $comments_authors_model = new User; // Author model
        $comments_authors_IDs = $model->getAuthorIDs($comments); # 2 step
        $comments_authors_info = $comments_authors_model->findByAuthorIDs($comments_authors_IDs); # 3 step
        /* - 1 step, 2 step... Steps...?! What is this?
           - See BlogController/actionIndex for more information. */

        # if comment is loaded
        if ($comments_model->load(Yii::$app->request->post())) {
            $comments_model->updateCommentsCount($model, "count++");
            $comments_model->saveComment($comments_model->getData());
            $this->refresh();
        } else {
            //return var_dump($category->checkAllPostsCategories($model));
            return $this->render('view', [
                'model'                   => $model,
                'blog'                    => $blog,
                'post_author'             => $post_author,
                'userHasPrivilegies_Post' => $model->checkUDPrivilegies($model, $blog),
                // checks user permissions
                'comments'                => $comments,
                'comments_authors_model'  => $comments_authors_model,
                'comments_authors_info'   => $comments_authors_info,
                'comments_pagination'     => $comments_pagination,
                'categories_all'          => $category->checkAllPostsCategories($model),
            ]);
        }
    }

    /**
     * Displays all posts which belongs to current user
     *
     * @param string $status Status of the post: draft or publish
     *
     * @return mixed
     */
    public function actionOurposts($status = 'all')
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
        $category = new Category;
        $model = new Post;
        $posts = $model->findOurPosts($status); // Find our posts with specified status

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount'      => $posts->count(),
        ]);

        return $this->render('ourPosts', [
            'posts'          => $posts->all(),
            'posts_author'   => User::findIdentity(Yii::$app->user->id),
            'blog'           => Blog::getBlogByAuthorId(Yii::$app->user->id),
            'pagination'     => $pagination,
            'categories_all' => $category->checkAllPostsCategories($posts->all()),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $blog_id Blog ID
     *
     * @return mixed
     * @throws Exception if the model cannot be saved
     */
    public function actionCreate($blog_id = null)
    {
        /* If blog id isn't null, also Blog ID must be set, user can post only into own blog */
        if (Yii::$app->user->isGuest) {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }

        if (($blog_id == null) || !(Blog::getBlogByAuthorId(Yii::$app->user->id)->id == $blog_id)) {
            return $this->redirect([
                'user/view',
                'id'    => Yii::$app->user->id,
                'error' => 'no_rights',
            ]);
        }
        $model = new Post();
        $model->categories_selection = explode(',', $model->categories);
        $blog = Blog::getBlogByAuthorId(Yii::$app->user->id);

        if ($model->checkCPrivilegies()) {
            if ($model->load(\Yii::$app->request->post())) {
                if ($model->savePost($model->getPostData())) {
                    if ($model->getPostData()->publish_status == 'publish') {
                        $blog->updatePostsCount($blog, 'count++');
                    }
                } else {
                    throw new HttpException('Error during creating post');
                }
                return $this->redirect([
                    'post/index',
                    'blog_id' => $blog_id,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect([
                'user/view',
                'id'    => Yii::$app->user->id,
                'error' => 'no_rights',
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     * @throws Exception if the model cannot be saved
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
        $model = $this->findModel($id);
        $blog = Blog::getBlogByAuthorId($model->author_id);
        // If this is an author or an admin
        if ($model->load(Yii::$app->request->post())) {
            if ($model->checkUDPrivilegies($model) && $blog->checkUDPrivilegies($blog)) {
                $model->categories = implode(',', $model->categories_selection);
                if ($model->savePost($model)) {
                    return $this->redirect([
                        'view',
                        'id'      => $model->id,
                        'blog_id' => $blog->id,
                    ]);
                } else {
                    throw new Exception('Error during updating post');
                }
            } else {
                return $this->redirect([
                    'user/view',
                    'error' => 'no_rights',
                    'id'    => Yii::$app->user->id,
                ]);
            }
        } else {
            if ($model->categories != '') {
                $category = new Category;
                $category->cleanAllDeletedCategoriesInPost($model);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     *
     * @param string $id
     * @param string $route | By default is null
     *
     * @return mixed
     * @throws Exception if the model cannot be saved
     */
    public function actionDelete($id, $route = 'index')
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
        $model = $this->findModel($id);
        $blog = Blog::getBlogByAuthorId($model->author_id);
        // If this is an author or an admin
        if ($model->checkUDPrivilegies($model) && $blog->checkUDPrivilegies($blog)) {
            $blog = Blog::getBlogByAuthorId($model->author_id);
            if ($model->getPostData()->publish_status == 'publish') {
                $blog->updatePostsCount($blog, 'count--');
            }
            if ($model->deletePost($model)) {
                if ($route == 'index') {
                    return $this->redirect([
                        'index',
                        'blog_id' => $blog->id,
                    ]);
                } else {
                    return $this->redirect([
                        'ourposts',
                        ['status' => $route],
                    ]);
                }
            } else {
                throw new Exception('Error during deleting post');
            }
        } else {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
