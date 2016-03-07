<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Post;
        $query = Post::find();
        $authors_model = new User;

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query
                        ->where('publish_status=:publish_status', [':publish_status' => 'publish'])
                        ->count(),
        ]);

        /*  === Showing username of the author of selected post ===
            Firstly we need to find all displayed posts;
            Secondly we count all author_id's of displayed posts
                and delete all repeating values from that array,
                also create a string contains all values from array separated by comma;
            Thirdly we need to use this array to provide the username in the query.   */
        $posts = $model->findAllDisplayedPosts($pagination); # 1 step
        $authors_IDs = $model->getAuthorIDs($posts); # 2 step
        $authors_info = $authors_model->findByAuthorIDs($authors_IDs); # 3 step

        return $this->render('index',[
            'posts' => $posts,
            'authors_model' => $authors_model,
            'authors_info' => $authors_info,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $post_author = $model->findAuthorUsername($id); // finds author username

        // find ALL comments with [comment.post_id == post.id]
        $comments_model = new Comment;
        $comments_pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $model->comments_count,
        ]);
        $comments = $comments_model->findPostComments($id, $comments_pagination); # 1 step
        $comments_authors_model = new User; // Author model
        $comments_authors_IDs = $model->getAuthorIDs($comments); # 2 step
        $comments_authors_info = $comments_authors_model->findByAuthorIDs($comments_authors_IDs); # 3 step
        /* - 1 step, 2 step... Steps...?! What is this?
           - See actionIndex for more information. */

        # if comment is loaded
        if ($comments_model->load(Yii::$app->request->post())) {
            if($_POST['status'] == 'ok') {
                $this->refresh();
            } else {
                $comments_model->updateCommentsCount($model, "count++");
                $comments_model->getData();
                $this->refresh();
            }
        } else {
            return $this->render('view', [
                'model' => $model,
                'post_author' => $post_author,
                'userHasPrivilegies_Post' => $model->checkUDPrivilegies($model), // checks user permissions
                'comments' => $comments,
                'comments_authors_model' => $comments_authors_model,
                'comments_authors_info' => $comments_authors_info,
                'comments_pagination' => $comments_pagination,
            ]);
        }
    }

    /**
     * Displays all posts which belongs to current user
     * @param string $status Status of the post: draft or publish
     * @return mixed
     */
    public function actionOurposts($status='all')
    {
        $model = new Post;
        $posts = $model->findOurPosts($status); // Find our posts with specified status

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $posts->count(),
        ]);

        return $this->render('ourPosts', [
            'posts' => $posts->all(),
            'post_author' => User::findIdentity(Yii::$app->user->id),
            'pagination' => $pagination,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if($model->checkCPrivilegies()) {
            if ($model->load(\Yii::$app->request->post())) {
                $model->getPostData();
                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else return $this->redirect('@app/views/site/error_user.php');
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // If this is an author or an admin
        if($model->checkUDPrivilegies($model)) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Deletes an existing Post model.
     * @param string $id
     * @param string $route | By default is null
     * @return mixed
     */
    public function actionDelete($id, $route = 'index')
    {
        $model = $this->findModel($id);
        // If this is an author or an admin
        if($model->checkUDPrivilegies($model)) {
            $model->delete();
            return $this->redirect('@app/views/posts/index.php');
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
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
