<?php

namespace app\controllers;

use app\models\forms\UserEditForm;
use app\models\forms\UserProfileForm;
use app\models\search\UserSearch;
use app\repositories\NotFoundException;
use app\services\SearchService;
use app\services\UserService;
use RuntimeException;
use Yii;
use app\models\TagSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * PostController implements the CRUD actions for Post model.
 */
class UserController extends Controller
{
    private $userService;
    private $searchService;

    public function __construct($id, $module, UserService $user, SearchService $search, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $user;
        $this->searchService = $search;
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
                        'actions' => ['update', 'index', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [   'actions' => ['profile', 'view'],
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


    /**
     * @param null $category_id
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
    public function actionView($id)
    {

        try {
            $user = $this->userService->get($id);
            $posts = $this->searchService->getByUserId($id);
            return $this->render('view', [
                'user' => $user,
                'posts' => $posts
            ]);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('danger', $e->getMessage());

            return $this->goBack();
        }
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
        $user = $this->userService->get($id);

        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->edit($user->id, $form);
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }catch(\yii\db\Exception $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'user' => $user,
        ]);
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
        return $this->redirect(['index']);
    }


    public function actionProfile()
    {
        $user = $this->userService->get(Yii::$app->user->id);

        $model = new UserProfileForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $this->userService->updateProfile(Yii::$app->user->identity->getId(), $model);
                Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('profile', ['model' => $model]);
    }

   // public function
}
