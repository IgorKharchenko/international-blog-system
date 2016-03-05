<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest) {
            $dataProvider = new ActiveDataProvider([
                'query' => Users::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest && $model->checkUDPrivilegies($model)) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest &&  $model->checkUDPrivilegies($model)) {

            if ($model->load(Yii::$app->request->post())) {
                $model->updated_at = time();
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
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
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest && $model->checkUDPrivilegies($model)) {
            $model->delete();
            return $this->redirect(['index']);
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
        $auth = Yii::$app->authManager;
        $alreadyHasAdmins = $model->alreadyHasAdmins();
        $isAdmin = $model->isAdmin();
        # True if we DON'T have any admins or current user is an admin
        if(!Yii::$app->user->isGuest && (!$alreadyHasAdmins || $isAdmin)) {

            $dataProvider = new ActiveDataProvider([
                'query' => User::find(),
            ]);
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => User::find()->count(),
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
                        'pagination' => $pagination,
                        'setRole' => ($role=='admin') ? 'Administrator' : 'Author',
                    ]);
                } else {
                    return $this->render('assign', [
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'modelIsNull' => false,
                        'pagination' => $pagination,
                    ]);
                }
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
