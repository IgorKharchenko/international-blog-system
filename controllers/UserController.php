<?php

namespace app\controllers;

use app\models\EarthCountries;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest) {
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => User::find()->count(),
            ]);
            $dataProvider = new ActiveDataProvider([
                'query' => User::find(),
                'pagination' => [
                    'pageSize' => 10,
                ]
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @throws HttpException if transaction is unsuccessful
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User;
        if ($model->load(Yii::$app->request->post())) {
            if($model->saveUser($model))
                return $this->redirect(['view', 'id' => $model->id]);
            else
                throw new HttpException(400, 'Error during saving comment info in the database');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @throws HttpException if transaction is unsuccessful
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest && $model->checkUDPrivilegies($model)) {

            if ($model->load(Yii::$app->request->post())) {
                if($model->saveUser($model)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else
                    throw new HttpException(400, 'Error during saving comment info in the database');
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect('@app/views/site/error_user.php');
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @throws HttpException if transaction is unsuccessful
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest && $model->checkUDPrivilegies($model)) {
            if($model->deleteUser($model))
                return $this->redirect(['index']);
            else
                throw new HttpException(400, 'Error during saving comment info in the database');
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Assigns a role to user.
     * First registered user in the blog is an admin who can assign roles to others.
     */
    public function actionAssign($id=null, $role=null)
    {
        $model = new User;
        $isAdmin = $model->isAdmin();
        # True if we DON'T have any admins or current user is an admin
        if(!Yii::$app->user->isGuest && $isAdmin) {
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => User::find()->count(),
            ]);
            $dataProvider = new ActiveDataProvider([
                'query' => User::find()
                            ->offset($pagination->offset)
                            ->limit($pagination->limit),
                'pagination' => [
                    'pageSize' => 10,
                ]
            ]);
            if(is_null($dataProvider))
            {
                return $this->render('assign', [
                    'dataProvider' => $dataProvider,
                    'modelIsNull' => true,
                ]);
            } else {
                if($id != null) {
                    $model->setRole($id, $role);
                    return $this->render('assign', [
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'modelIsNull' => false,
                        'setRole' => ($role=='admin') ? 'Administrator' : 'Author',
                        'assigned_user' => User::findIdentity($id),
                    ]);
                } else {
                    return $this->render('assign', [
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'modelIsNull' => false,
                    ]);
                }
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    public function actionShowEmail($mode = 'hide')
    {
        $user = $this->findModel(Yii::$app->user->id);
        if($mode == 'show')
        {
            $user->show_email = 1;
            $user->saveUser($user);
            return $this->redirect(['site/admin', 'status_em' => 'ok']);
        } elseif($mode == 'hide')
        {
            $user->show_email = 0;
            $user->saveUser($user);
            return $this->redirect(['site/admin', 'status_em' => 'ok']);
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User| The loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
