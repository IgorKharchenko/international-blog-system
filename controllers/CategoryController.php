<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Blog;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex($blog_id)
    {
        if(!Yii::$app->user->isGuest) {
            $dataProvider = new ActiveDataProvider([
                'query' => Category::find()->where(['blog_id' => $blog_id]),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } else
            return $this->redirect(['site/login', 'logined' => 'false']);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * Logic: one blog have many categories, but different blogs have different categories
     * @return mixed
     * @throws Exception if this is an error during creating process
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->isGuest) {
            $blog = Blog::getBlogByAuthorId(Yii::$app->user->id);
            if($blog->checkUDPrivilegies($blog)) {
                $model = new Category();
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->saveCategory($model->getData()))
                        return $this->redirect(['index', 'blog_id' => Blog::getBlogByAuthorId(Yii::$app->user->id)->id]);
                    else
                        throw new Exception('Error during creating a category');
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else return $this->redirect(['user/view', 'id' => Yii::$app->user->id, 'error' => 'no_rights']);
        } else return $this->redirect(['site/login', 'logined' => 'false']);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws Exception if this is an error during saving process
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->user->isGuest) {
            $blog = Blog::getBlogByAuthorId(Yii::$app->user->id);
            if($blog->checkUDPrivilegies($blog)) {
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post())) {
                    if($model->saveCategory($model))
                        return $this->redirect(['index', 'blog_id' => Blog::getBlogByAuthorId(Yii::$app->user->id)->id]);
                    else
                        throw new Exception('Error during saving a category');
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else return $this->redirect(['user/view', 'id' => Yii::$app->user->id, 'error' => 'no_rights']);
        } else return $this->redirect(['site/login', 'logined' => 'false']);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Exception if this is an error during deleting process
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->isGuest) {
            $blog = Blog::getBlogByAuthorId(Yii::$app->user->id);
            if($blog->checkUDPrivilegies($blog)) {
                $model = $this->findModel($id);
                if($model->deleteCategory($model))
                    return $this->redirect(['index', 'blog_id' => Blog::getBlogByAuthorId(Yii::$app->user->id)->id]);
                else
                    throw new Exception('Error during deleting a category');
            }
        } else return $this->redirect(['site/login', 'logined' => 'false']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
