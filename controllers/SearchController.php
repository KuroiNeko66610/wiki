<?php

namespace app\controllers;

use app\models\Category;
use app\models\Post;
use app\models\search\PostSearch;
use app\services\PostService;
use app\services\SearchService;
use app\services\TagService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;


class SearchController extends Controller
{

    private $searchService;
    private $postService;
    private $tagService;

    public function __construct($id, $module, PostService $post,TagService $tag, SearchService $search, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->searchService = $search;
        $this->postService = $post;
        $this->tagService = $tag;
    }

    public function behaviors()
    {
        return [
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
        ];
    }

      /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex($controller , $id)
    {
        if($controller === 'post'){
            $post = $this->postService->get($id);
            return $this->renderAjax('post', ['model' => $post]);
        }

        if($controller === 'category'){
            $post = $this->searchService->searchByCategory($id);
            return $this->renderAjax('category', ['model' => $post]);
        }
    }

    public function actionFull(){
        //(empty(Yii::$app->request->post('')));
        $searchModel = new PostSearch(['status' => Post::STATUS_APPROVED]);
        $dataProvider = '';
        if(!empty(Yii::$app->request->post('PostSearch'))){
            $dataProvider = $searchModel->search(Yii::$app->request->post());
        }

        $tags = $this->tagService->getArray();

        if (Yii::$app->request->isPjax) {
            return $this->renderPartial('full', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tags' => $tags
            ]);
        } else {
            return $this->render('full', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tags' => $tags
            ]);
        }

    }


    public function actionQuick($q = ''){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $search = $this->searchService->search($q);
        return $search;
    }
    
}
