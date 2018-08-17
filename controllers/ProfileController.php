<?php

namespace app\controllers;


use app\services\UserProfileService;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;


/**
 * PostController implements the CRUD actions for Post model.
 */
class ProfileController extends Controller
{
    private $profileService;


    public function __construct($id, $module, UserProfileService $ProfileService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->profileService = $ProfileService;
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
                    ['actions' => ['add-favorites', 'remove-favorites'],
                        'allow' => true,
                        'roles' => ['author'],
                    ]
                ],
            ]
        ];
    }

    public function actionAddFavorites($post_id){
        try {
            $this->profileService->addToFavorites(Yii::$app->user->identity->profile, $post_id);
            return true ;
        } catch (\RuntimeException $e) {
            return false;
        }

    }

    public function actionRemoveFavorites($post_id){
        try {
            $this->profileService->RemoveFavorites(Yii::$app->user->identity->profile, $post_id);
            return true ;
        } catch (\RuntimeException $e) {
            return false;
        }
    }

}

