<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\services\auth\SignupService;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
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

    public function actionRbac(){
        $auth = Yii::$app->authManager;
       /* $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Администратор';
        Yii::$app->authManager->add($role);

        $role = Yii::$app->authManager->createRole('moderator');
        $role->description = 'Модератор';
        Yii::$app->authManager->add($role);

        $role = Yii::$app->authManager->createRole('author');
        $role->description = 'Автор';
        Yii::$app->authManager->add($role);

        $role = Yii::$app->authManager->createRole('guest');
        $role->description = 'Гость';
        Yii::$app->authManager->add($role);



        $rule = new \app\rbac\rules\OwnModelRule;
        $auth->add($rule);

        $updateOwnPost = $auth->createPermission('OwnModelRule');
        $updateOwnPost->description = 'Право собвстенности';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        $parent = Yii::$app->authManager->getRole('author');
        $child = Yii::$app->authManager->getRole('moderator');
        Yii::$app->authManager->addChild($parent, $child);

        */



        echo 'done!';

        return $this->render('rbac');
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

}
