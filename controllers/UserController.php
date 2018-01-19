<?php

namespace app\controllers;

use Yii;
use app\models\EarthCountries;
use app\models\TimeOffset;
use app\models\User;
use app\models\Blog;
use yii\data\ArrayDataProvider;
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
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all User models.
     * All registered users can view full users list,
     *      but yes, only admins can update/delete users from this list.
     * TODO: There MUST be normal realization of timezone offset!
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if (!$model->isAdmin()) {
            return $this->redirect([
                'user/view',
                'id'    => Yii::$app->user->id,
                'error' => 'no_rights',
            ]);
        }
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount'      => User::find()->count(),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query'      => User::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pagination'   => $pagination,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $blog = Blog::getBlogByAuthorId($id);
        $user = User::findOne(Yii::$app->user->identity->getId());
        $canUserSeeBlog = $blog->is_private === 0 || $user->isAdmin();

        return $this->render('view', [
            'model'          => $model,
            'blog'           => $blog,
            'canUserSeeBlog' => $canUserSeeBlog,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @throws HttpException if transaction is unsuccessful
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->checkUDPrivilegies($model) && $model->load(Yii::$app->request->post())) {
            TimeOffset::setTimezoneCookie($model->timezone);
            if ($model->saveUser($model)) {
                return $this->redirect([
                    'view',
                    'id' => $model->id,
                ]);
            } else {
                throw new HttpException(400, 'Error during saving comment info in the database');
            }
        } else {
            return $this->render('update', [
                'model'         => $model,
                'timeZonesList' => $model->getTimeZoneSelect(),
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * The cookie with timezone value isn't being deleted.
     *
     * @throws HttpException if transaction is unsuccessful
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->isGuest) {
            if ($model->checkUDPrivilegies($model)) {
                if ($model->deleteUser($model)) {
                    return $this->goHome();
                } else {
                    throw new HttpException(400, 'Error during saving comment info in the database');
                }
            } else {
                return $this->redirect([
                    'user/view',
                    'id'    => Yii::$app->user->id,
                    'error' => 'no_rights',
                ]);
            }
        } else {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
    }

    /**
     * Assigns a role to user.
     * For assign a first admin you need to run 'yii rbac/init-first-admin [id]',
     * where [id] (without brackets) is an ID of registered user
     */
    public function actionAssign($id = null, $role = null)
    {
        $model = new User;
        $isAdmin = $model->isAdmin();
        # True if current user is an admin
        if (!Yii::$app->user->isGuest) {
            if ($isAdmin) {
                $pagination = new Pagination([
                    'defaultPageSize' => 10,
                    'totalCount'      => User::find()->count(),
                ]);
                $dataProvider = new ActiveDataProvider([
                    'query'      => User::find()
                                        ->offset($pagination->offset)
                                        ->limit($pagination->limit),
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
                if (is_null($dataProvider)) {
                    return $this->render('assign', [
                        'dataProvider' => $dataProvider,
                        'modelIsNull'  => true,
                    ]);
                } else {
                    if ($id != null) {
                        $model->setRole($id, $role);
                        return $this->render('assign', [
                            'dataProvider'  => $dataProvider,
                            'model'         => $model,
                            'modelIsNull'   => false,
                            'setRole'       => ($role == 'admin') ? 'Administrator' : 'Author',
                            'assigned_user' => User::findIdentity($id),
                        ]);
                    } else {
                        return $this->render('assign', [
                            'dataProvider' => $dataProvider,
                            'model'        => $model,
                            'modelIsNull'  => false,
                        ]);
                    }
                }
            } else {
                return $this->redirect([
                    'user/view',
                    'id'    => Yii::$app->user->id,
                    'error' => 'no_rights',
                ]);
            }
        } else {
            return $this->redirect([
                'site/login',
                'logined' => 'false',
            ]);
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return User| The loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested user does not exist.');
        }
    }
}
