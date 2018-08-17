<?php

namespace app\controllers;

use app\models\search\PostSearch;
use app\repositories\NotFoundException;
use app\services\SearchService;
use app\services\TagService;
use Yii;
use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\services\PostService;
use yii\web\Session;

/**
 * PostController implements the CRUD actions for Post model.
 */
class ModeratorController extends Controller
{
    private $postService;

    public function __construct($id, $module, PostService $post, TagService $tag, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $post;
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),

                'rules' => [
                    [   'actions' => ['pending'],
                        'allow' => true,
                        'roles' => ['author'],
                    ],
                    [   'actions' => ['index', 'view', 'approve', 'reject'],
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'pending' => ['POST'],
                    'approve' => ['POST']
                ],
            ],
        ];
    }


    /**
     * @param null $category_id
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new PostSearch(['status' => Post::STATUS_PENDING]);
        $dataProvider = $searchModel->search(  Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
/*
    public function actionView($id)
    {
        try {
            $model =  $this->postService->get($id, [Post::STATUS_PENDING]);
            return $this->render('../post/view', [
                'model' => $model
            ]);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());

            return $this->redirect(['moderator/index']);
        }
    }
    */


        public function actionApprove($id){
       // $post = $this->postService->get($id, [Post::STATUS_PENDING]);
        try {
            $this->postService->approve($id);
            Yii::$app->session->setFlash('success', 'Статья одобрена');
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);

    }

    public function actionReject($id){
       // $post = $this->postService->get($id, [Post::STATUS_PENDING, Post::STATUS_APPROVED]);
        try {
            $this->postService->reject($id);
            Yii::$app->session->setFlash('success', 'Статья отклонена');
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['moderator/index']);

    }

    public function actionPending($id){
        $model = $this->postService->get($id, [Post::STATUS_DRAFT]);

        if(! Yii::$app->user->can('UpdateModel', ['model' => $model]))
            throw new ForbiddenHttpException('У вас недостаточно прав для выполнения указанного действия');

        try {
            $this->postService->pending($id);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['post/view', 'id' => $model->id]);

    }

}
