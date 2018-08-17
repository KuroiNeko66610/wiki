<?php

namespace app\controllers;

use app\models\Correction;
use app\models\search\CorrectionSearch;
use app\repositories\NotFoundException;
use app\services\CorrectionService;
use app\services\SearchService;
use app\services\TagService;
use Yii;
use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\gii\components\DiffRendererHtmlInline;
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
class CorrectionController extends Controller
{
    private $postService;
    private $correctionService;


    public function __construct($id, $module, PostService $post, CorrectionService $correction, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $post;
        $this->correctionService = $correction;
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
                    [
                        'actions' => ['index', 'view', 'update-post', 'reject'],
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                    [   'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['author'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new CorrectionSearch(['status' => Correction::STATUS_PENDING]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $post = $this->postService->get($id);
        $model = new Correction();
        $model->text = $post->description;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $correction = $this->correctionService->create($id, $model);
                Yii::$app->session->setFlash('success', 'Дополнение к статье создано. Ожидайте ответа модератора');
                return $this->redirect(['post/view', 'id' => $id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['post/view', 'id' => $id]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionView($id)
    {
            try {
                $correction = $this->correctionService->get($id);
                $post = $this->postService->get($correction->post_id);

            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['post/view', 'id' => $id]);
            }


        return $this->render('view', [
            'correction' => $correction,
            'post' => $post
        ]);
    }


    public function actionApprove($id){
        try {
            $correction = $this->correctionService->get($id);
            $this->correctionService->approve($id);
            Yii::$app->session->setFlash('success', 'Дополннение одобрено');
            return $this->redirect(['post/view','id' => $correction->post_id]);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionUpdatePost($id){
       try {
           $correction = $this->correctionService->get($id);
           $this->postService->updateDescription($correction->post_id, $correction->text);
           $this->correctionService->approve($id);
           Yii::$app->session->setFlash('success', 'Текст статьи обновлен');
           return $this->redirect(['post/view','id' => $correction->post_id]);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionReject($id){
        try {
            $correction = $this->correctionService->get($id);
            $this->correctionService->reject($id);
            Yii::$app->session->setFlash('warning', 'Дополннение отколено');
            return $this->redirect(['post/view','id' => $correction->post_id]);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }
    }
}
