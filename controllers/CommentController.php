<?php

namespace app\controllers;

use app\models\Post;
use Yii;
use app\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
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
     * Displays a single Comment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($post_id)
    {
        $model = new Comment();
        $post = Post::findOne($post_id);
        if($model->checkCPrivilegies()) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $model->updateCommentsCount($post, "count++");
                return $this->redirect(['post\view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Updates an existing Comment.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id, $post_id=null)
    {
        $model = $this->findModel($id);
        if($model->checkUDPrivilegies($model)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['post/view', 'id' => $post_id]);
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
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $post_id=null)
    {
        $model = $this->findModel($id);
        $post = Post::findOne($post_id);
        if($model->checkUDPrivilegies($model)) {
            $model->updateCommentsCount($post, "count--");
            $model->delete();
            return $this->redirect('@app/views/post/view.php?id='.$post_id.'&status=ok');
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
