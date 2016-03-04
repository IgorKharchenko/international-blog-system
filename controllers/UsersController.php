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
        if(!Yii::$app->user->isGuest && Yii::$app->user->can('updateOwnUser', ['user' =>  $this->findModel($id)])) {
            return $this->render('view', [
                'model' => $this->findModel($id),
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
        if(!Yii::$app->user->isGuest && (Yii::$app->user->can('updateUser') || Yii::$app->user->can('updateOwnUser', ['user' => $this->findModel($id)]) )) {
            $model = $this->findModel($id);

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
        if(!Yii::$app->user->isGuest && (Yii::$app->user->can('deleteUser') || Yii::$app->user->can('deleteOwnUser', ['user' => $this->findModel($id)]) )) {
            $this->findModel($id)->delete();

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
        # Checks if there are any admins in the blog
        $getAlreadyHasAdmins = $auth->getUserIdsByRole('admin');
        empty($getAlreadyHasAdmins) ? $alreadyHasAdmins = false : $alreadyHasAdmins = true;
        # Checks if user have an admin rights
        $getIsAdmin = $auth->getRolesByUser(Yii::$app->user->id);
        $isAdmin = (ArrayHelper::getValue($getIsAdmin, 'admin')) ? true : false;
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
                    $authorRole = $auth->getRole('author');
                    $adminRole = $auth->getRole('admin');
                    $getRoles = $auth->getRolesByUser($id);
                    if($role != null) {
                        if($role == 'admin') {
                            if(ArrayHelper::getValue($getRoles, 'author'))
                                $auth->revoke($authorRole, $id);
                            if(ArrayHelper::getValue($getRoles, 'admin') == null)
                                $auth->assign($adminRole, $id);
                        } elseif($role == 'author') {
                            if(ArrayHelper::getValue($getRoles, 'admin'))
                                $auth->revoke($adminRole, $id);
                            if(ArrayHelper::getValue($getRoles, 'author') == null)
                                $auth->assign($authorRole, $id);
                        }
                    }
                    return $this->render('assign', [
                        'dataProvider' => $dataProvider,
                        'model' => $model,
                        'modelIsNull' => false,
                        'pagination' => $pagination,
                        'assignRole' => ($role=='admin') ? 'Administrator' : 'Author',
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
