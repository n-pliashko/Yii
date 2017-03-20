<?php

namespace app\controllers;

use app\models\Customer;
use app\models\RegisterForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\widgets\ListView;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'register'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'],
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


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
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

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $customer = new Customer();
//        $customer = new RegisterForm();
        if ($customer->load(Yii::$app->request->post()) && $customer->register()) {
            Yii::$app->user->login($customer, 0);
//            Yii::$app->user->login($customer->getUser(), 0);
            return $this->goBack('/thank_register');
        }
        return $this->render('register' , compact('customer'));
    }


    public function actionThank_register()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $customer = Yii::$app->user->getIdentity(false);
        return $this->render('thank_register' , compact('customer'));
    }

    public function actionUlists()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Customer::find()->orderBy('created DESC'),
            'pagination' => [
                'pagesize' => 5,
            ],
        ]);
        $this->view->title = 'News List';
        return $this->render('customers_list', ['dataProvider' => $dataProvider]);
    }
}
