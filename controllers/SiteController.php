<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\UserRule;
use Yii;
use app\models\Customers;
use app\models\CustomerSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SiteController implements the CRUD actions for Customers model.
 */
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
                'only' => ['logout', 'register', 'login', 'update', 'view', 'create', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['update', 'view', 'create', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['update', 'view'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $auth = Yii::$app->authManager;

        $view = $auth->createPermission('view');
        $view->description = 'View user';
        $auth->add($view);

        $create = $auth->createPermission('create');
        $create->description = 'Create user';
        $auth->add($create);

        $update = $auth->createPermission('update');
        $update->description = 'Update user';
        $auth->add($update);

        $delete = $auth->createPermission('delete');
        $delete->description = 'Delete user';
        $auth->add($delete);

        //add rule - update own
        $rule = new UserRule();
        $auth->add($rule);
        $updateOwn = $auth->createPermission('updateOwn');
        $updateOwn->description = 'Update own';
        $updateOwn->ruleName = $rule->name;
        $auth->add($updateOwn);

        $user = $auth->createRole('user');
        $auth->add($user);
        if (!$auth->hasChild($user, $view)) {
            $auth->addChild($user, $view);
            $auth->addChild($updateOwn, $update);
            $auth->addChild($user, $updateOwn);
        }

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        if (!$auth->hasChild($admin, $update)) {
            $auth->addChild($admin, $update);
            $auth->addChild($admin, $delete);
            $auth->addChild($admin, $create);
            $auth->addChild($admin, $user);
            $auth->addChild($admin, $updateOwn);
        }
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
     * Lists all Customers models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionCustomers()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('view')) {
            return $this->goHome();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('create')) {
            return $this->goHome();
        }

        $model = new Customers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateOwn', ['post' => $id]) && !Yii::$app->user->can('update')) {
            return $this->goHome();
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete')) {
            return $this->goHome();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['customers']);
    }

    /**
     * Finds the Customers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customers::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole($model->getUser()->role);
            Yii::$app->authManager->assign($authorRole, $model->getUser()->id);
            return $this->goBack();
        }

        $this->view->title = 'Login';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        return $this->render('login_canjs', [
            'model' => $model,
            'error' => Yii::$app->request->getIsPost() ?  'Not exist such user' : '',
        ]);
    }


    public function actionLogin_canjs()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $result['status'] = 'success';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole($model->getUser()->role);
            Yii::$app->authManager->assign($authorRole, $model->getUser()->id);
            $result['redirect'] = Yii::$app->getUser()->getReturnUrl();
            return Json::encode($result);//$this->goBack();
        }

        $this->view->title = 'Login';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        return Json::encode([
            'status' => 'error',
            'model'  => $model,
            '_csrf'    => Yii::$app->request->getCsrfToken(),
            'error'  => Yii::$app->request->getIsPost() ?  'Incorrect email or password' : '',
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->authManager->revokeAll(Yii::$app->user->getId());
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $customer = new Customers();
        $customer->scenario = 'register';
        if ($customer->load(Yii::$app->request->post()) && $customer->save()) {
            Yii::$app->user->login($customer, 0);
            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole($customer->role);
            $auth->assign($userRole, $customer->id);
            return $this->goBack('/web/site/thank_register');
        }

        $this->view->title = 'Register';
        $this->view->params['breadcrumbs'][] = $this->view->title;
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
            'query' => (new Query())
                ->from(Customers::tableName())
                ->indexBy('id')
                ->orderBy('created DESC'),
            'pagination' => [
                'pagesize' => 5,
            ],
        ]);
        $this->view->title = 'News List';
        return $this->render('customers_list', ['dataProvider' => $dataProvider]);
    }
}
