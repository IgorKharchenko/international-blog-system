<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\TimeOffset;
use app\models\Post;
use app\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;


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
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findOne(Yii::$app->user->id);
            $user->last_login = time();
            TimeOffset::setTimezoneCookie($user->timezone);
            if($user->saveUser($user))
                return $this->goBack();
            else
                throw new HttpException('400', 'Error during saving user info');
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
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if(Yii::$app->user->isGuest) {
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                }
            }
            $user = new User;
            return $this->render('signup', [
                'model' => $model,
                'timeZonesList' => $user->getTimeZoneSelect(),
            ]);
        }
        return $this->render('login', [
            'model' => new LoginForm(),
        ]);
    }

    /**
     * Admin panel for admins and console for authors in one page!
     * @var isAdmin true if current user have an admin rights
     * @return string
     */
    public function actionConsole()
    {
        $user = new User;
        $isAdmin = $user->isAdmin();

        if(!Yii::$app->user->isGuest) {
            if($isAdmin) {
                return $this->render('console', [
                    'model' => $user,
                    'assign_permitted' => true,
                ]);
            } else {
                return $this->render('console', [
                    'model' => $user,
                    'assign_permitted' => false,
                ]);
            }
        } else {
            return $this->redirect(['site/login', 'logined' => 'false']);
        }
    }

    /**
     * Playground action for testing some features. Will be deleted in the future, por supuesto :D
     */
    public function actionPlayground()
    {
        $category_model = new \app\models\Category;
        return var_dump($category_model->checkCategoriesString('1,3,4,5,7,10,14,21,24'));


        $model = new Post;
        $formatter = Yii::$app->formatter;
        $posts = Post::find()->all();
        foreach($posts as $post){
            return var_dump($post->publish_date);
        }
        //$before = $formatter->asDatetime($posts->publish_date);

        $getPossst = $model->setOffsetForTimestamps_View($posts);
        //$after = $formatter->asDatetime($getPossst->publish_date);

        //return var_dump(['Before' => $before, 'After' => $after, 'Equals?' => $before == $after]);
        foreach($posts as $post){
            return var_dump($post);
        }

        $array = ['Offset' => TimeOffset::getUserOffset(), 'This Time' => time()];
        return var_dump($array);
        return $this->render('playground');
    }
}
