<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use app\models\User;
use yii\data\ActiveDataProvider;
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

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $posts = $query
            ->where('publish_status=:publish', [':publish' => 'publish'])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $users = User::find()
            ->where(['id' => [$posts->author_id]])
            ->all();

        $comments_count = Comment::find()
            ->where(['post_id' => $model->id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->count();

        return $this->render('@app/views/post/index.php',[
            'model' => $model,
            'posts' => $posts,
            'users' => $users,
            'comments_count' => $comments_count,
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
        $query = Comment::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $comment_model = new Comment;
        $comments = $query
            ->where(['post_id' => $id])
            ->orderBy('publish_date')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $comments_count = $query->count();

        # find all users which ID equals to post author ID
        # First: SELECT author_id FROM post WHERE id=$id
        # Second: SELECT * FROM user WHERE id=$author_id
        $author_id = Post::find()
            ->select('author_id')
            ->where('id=:post_id', [':post_id' => $id])
            ->one();
        $user_model = User::find()
            ->where('id=:author_id', ['author_id' => $author_id->author_id])
            ->one();

        # if comment is loaded
        if ($comment_model->load(Yii::$app->request->post()) && $comment_model->save()) {
            return $this->redirect(['view','id' => $id]);
        } else {
            return $this->render('view', [
                'model' => $model,
                'comment_model' => $comment_model,
                'comments' => $comments,
                'comments_count' => $comments_count,
                'author_model' => $user_model,
                'pagination' => $pagination,
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
        $query = Post::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        if($status == 'all') {
            $posts = $query
                ->where('author_id=:author_id', [':author_id' => Yii::$app->user->id])
                ->all();
        } else {
            $posts = $query
                ->where('publish_status=:status', [':status' => $status])
                ->andWhere('author_id=:author_id', [':author_id' => Yii::$app->user->id])
                ->all();
        }

        return $this->render('ourPosts', [
            'model' => $model,
            'posts' => $posts,
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
        if(\Yii::$app->user->can('createPost')) {

            $model = new Post();

            if ($model->load(\Yii::$app->request->post())) {

                $model->save();

                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                $model->author_id = Yii::$app->user->id;
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
        if(Yii::$app->user->can('updateOwnPost', ['post' => $model]) || Yii::$app->user->can('updatePost', ['post' => $model])  ) {

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
     * With the post deletion all the comments deletion is needed too.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // If this is an author or an admin
        if(Yii::$app->user->can('deletePost', ['post' => $model]) || Yii::$app->user->can('deleteOwnPost', ['post' => $model])  ) {

            $model->delete();
            return $this->redirect(['index']);
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
