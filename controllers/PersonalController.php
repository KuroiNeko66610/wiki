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
class PersonalController extends Controller
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
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60*60,
                'variations' => [
                    \Yii::$app->user->identity->id,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM wk_post WHERE user_id = '.Yii::$app->user->id,
                ],
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['favorites'],
                'duration' => 60*60,
                'variations' => [
                    \Yii::$app->user->identity->id,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT favorites FROM wk_user_profile WHERE user_id = '.Yii::$app->user->id,
                ],
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['drafts'],
                'duration' => 60*60,
                'variations' => [
                    \Yii::$app->user->identity->id,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM wk_post WHERE status = 10 AND user_id = '.Yii::$app->user->id,
                ],
            ],

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
    public function actionIndex()
    {
        // var_dump(Yii::$app->request->queryParams); die();
        $searchModel = new PostSearch(['user_id' => Yii::$app->user->id]);
        Yii::$app->request->queryParams;
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

    public function actionDrafts()
    {
        try {
            $model =  $this->postService->getDrafts(Yii::$app->user->id);
            return $this->render('drafts', [
                'data_provider' => $model
            ]);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());

            return $this->redirect(['../post/index']);
        }
    }

    public function actionFavorites(){
        try {
            $model =  $this->postService->getFavorites(Yii::$app->user->identity->profile->favorites);
            return $this->render('favorites', [
                'data_provider' => $model
            ]);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());

            return $this->redirect(['../post/index']);
        }
    }

}
