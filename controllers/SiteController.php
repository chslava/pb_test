<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\ForbiddenHttpException;

class SiteController extends Controller
{
    const LOGIN_DELAY = 300; //delay time in seconds when site has blocked
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $attempts = Yii::$app->session['login_attempts'];
                            if (is_array($attempts) && $attempts['attempt'] > 3) {
                                if ($attempts['timestamp'] +self::LOGIN_DELAY > time()) {
                                    return false;
                                } else {
                                    Yii::$app->session['login_attempts'] = null;
                                }
                            }

                            return true;
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if($action->id == 'login'){
                        $attempts = Yii::$app->session['login_attempts'];
                        $delay = is_array($attempts) ? $attempts['timestamp'] - time() + self::LOGIN_DELAY : '';
                        Yii::$app->session->setFlash('error', 'Вход заблокирован, попробуйте через ' . $delay . ' секунд');

                        $this->redirect(array('/site/index',302));
                    } else {
                        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                    }

                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {


        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('account');
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(array('/site/login',302));
    }

}
