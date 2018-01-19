<?php

namespace app\controllers;

use Yii;
use app\models\Blog;
use app\models\Post;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
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
     * Lists all Posts in current blog
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $blog_model = new Blog;
        $post_model = new Post;
        $query = Blog::find();
        $authors_model = new User;

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount'      => $query->count(),
        ]);

        /*  === Showing username of the author of selected blog ===
            — Firstly we need to find all displayed blogs;
            — Secondly we count all author_id's of displayed blogs
                and delete all repeating values from that array,
                also create a string contains all values from array separated by comma;
            — Thirdly we need to use this array to provide the username in the query.   */
        $blogs = $blog_model->findAllDisplayedBlogs($pagination); # 1 step
        $authors_IDs = $post_model->getAuthorIDs($blogs); # 2 step
        $authors_info = $authors_model->findByAuthorIDs($authors_IDs); # 3 step

        return $this->render('index', [
            'blogs'         => $blogs,
            'authors_model' => $authors_model,
            'authors_info'  => $authors_info,
            'pagination'    => $pagination,
        ]);
    }

    /**
     * Creates a new Blog model.
     * By app logic, there is only one blog for one user.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws Exception if this is an error during creating process
     */
    public function actionCreate()
    {
        $model = new Blog();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->getBlogByAuthorId(Yii::$app->user->id) == null) { // If user don't have a blog
                if ($model->saveBlog($model->getData())) {
                    return $this->redirect([
                        'user/view',
                        'id'   => Yii::$app->user->id,
                        'blog' => 'created',
                    ]);
                } else {
                    throw new Exception('Error during deleting a blog');
                }
            } else {
                return $this->redirect([
                    'user/view',
                    'id'    => Yii::$app->user->id,
                    'error' => 'blog_create',
                ]);
            }
        } else {
            if ($model->getBlogByAuthorId(Yii::$app->user->id) == null) { // If user don't have a blog
                return $this->render('create', [
                    'model' => $model,
                ]);
            } else {
                return $this->redirect([
                    'user/view',
                    'id'    => Yii::$app->user->id,
                    'error' => 'blog_create',
                ]);
            }
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws Exception if this is an error during updating process
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->checkUDPrivilegies($model)) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->saveBlog($model)) {
                    return $this->redirect([
                        'post/index',
                        'blog_id' => $model->id,
                    ]);
                } else {
                    throw new Exception('Error during deleting a blog');
                }
            } else {
                return $this->render('update', [
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
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws Exception if this is an error during deleting process
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->checkUDPrivilegies($model)) {
            if ($model->deleteBlog($model)) {
                return $this->redirect(['index']);
            } else {
                throw new Exception('Error during deleting a blog');
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
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested blog does not exist.');
        }
    }
}
