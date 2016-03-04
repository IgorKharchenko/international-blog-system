<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Post;
use app\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

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

        return $this->render('@app/views/post/index.php',[
            'model' => $model,
            'posts' => $posts,
            'pagination' => $pagination,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if(Yii::$app->user->isGuest) {
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                }
            }
            return $this->render('signup', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Admin panel.
     * and assignment to other users.
     * @var $alreadyRegistered true if an admin privilegies have been assigned to ANY registered user in the blog
     * @var isAdmin true if current user already have an admin rights
     * @return string
     */
    public function actionAdmin()
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
        if(!Yii::$app->user->isGuest) {
            if(!$alreadyHasAdmins || $isAdmin) {
                return $this->render('admin', [
                    'model' => $model,
                    'assign_permitted' => true,
                ]);
            } else {
                return $this->render('admin', [
                    'model' => $model,
                    'assign_permitted' => false,
                ]);
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }
}
