<?php

namespace app\controllers;

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
class PostController extends Controller
{
    private $postService;
    private $searchService;
    private $tagService;

    public function __construct($id, $module, PostService $post, SearchService $search, TagService $tag, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $post;
        $this->searchService = $search;
        $this->tagService = $tag;
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
          /*  [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,
                'variations' => [
                    \Yii::$app->user->identity->id,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM wk_post',
                ],
            ],
            */
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                    'allow' => true,
                    'roles' => ['author'],
                    ],
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


    /**
     * @param null $category_id
     * @return string
     */
    public function actionIndex($id = 0)
    {
        try {
            if($id === 0) {
                $search_all = $this->postService->getLastPosts();
                $title = "Последние статьи";
            }
            else {
                $search_all = $this->searchService->searchByCategory($id);
                $title = $this->searchService->getCategory($id);
                $title = $title['name'];
            }
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return $this->redirect(['post/index']);
        }

        return $this->render('index', [
            'title' => $title,
            'data_provider' => $search_all
        ]);

    }

    public function actionTag($slug){
        try {
            $data_provider = $this->searchService->searchByTag($slug);
            $tag = $this->tagService->getBySlug($slug);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return $this->redirect(['post/index']);
        }

        return $this->render('tag', [
            'data_provider' => $data_provider,
            'tag' => $tag
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
            try {
                $model =  $this->postService->get($id);
                return $this->render('view', [
                    'model' => $model
                ]);
            } catch (NotFoundException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('danger', $e->getMessage());

                return $this->redirect(['post/index']);
    }
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $post = $this->postService->create($model);
                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['create']);
            }
        }
        $tags = $this->tagService->getArray();
        return $this->render('create', [
            'model' => $model,
            'tags' => $tags
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->postService->get($id);

        //var_dump($model); die();
        if(! Yii::$app->user->can('UpdateModel', ['model' => $model]))
            throw new ForbiddenHttpException('У вас недостаточно прав для выполнения указанного действия');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $this->postService->edit($id, $model);
                Yii::$app->session->setFlash('success',"Статья сохранена");
                return $this->redirect(['view', 'id' => $id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['update', 'id' => $id]);
            }
        }

        $tags = $this->tagService->getArray();
        return $this->render('update', [
            'model' => $model,
            'tags' => $tags
        ]);
    }

    public function actionPending($id){
        $model = $this->postService->get($id, Post::STATUS_DRAFT);

        if(! Yii::$app->user->can('UpdateModel', ['model' => $model]))
            throw new ForbiddenHttpException('У вас недостаточно прав для выполнения указанного действия');

        try {
            $this->postService->pending($id);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);

    }
    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->postService->get($id);

        if(! Yii::$app->user->can('UpdateModel', ['model' => $model]))
            throw new ForbiddenHttpException('У вас недостаточно прав для выполнения указанного действия');

        try {
            $this->postService->remove($model);
            Yii::$app->session->setFlash('warning', "Статья удалена");
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);

    }

    public function actionFileUpload(){
        var_dump($_POST);
    }
}
