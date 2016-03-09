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
use app\models\Comment;
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
        $tmp_str = $model->getAuthorIDs($posts); # 2 step
        $authors_info = $authors_model->findByAuthorIDs($tmp_str, $pagination); # 3 step

        return $this->render('@app/views/post/index.php',[
            'posts' => $posts,
            'authors_info' => $authors_info,
            'authors_model' => $authors_model,
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
        $user = User::findOne(Yii::$app->user->id);
        $user->last_login = time();
        $user->saveUser($user);
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
        return $this->render('login', [
            'model' => new LoginForm(),
        ]);
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
        $user = new User;
        $isAdmin = $user->isAdmin();

        # True if we DON'T have any admins or current user is an admin
        if(!Yii::$app->user->isGuest) {
            if($isAdmin) {
                return $this->render('admin', [
                    'model' => $user,
                    'assign_permitted' => true,
                ]);
            } else {
                return $this->render('admin', [
                    'model' => $user,
                    'assign_permitted' => false,
                ]);
            }
        } else {
            return $this->redirect('@app/views/site/login.php');
        }
    }
}
