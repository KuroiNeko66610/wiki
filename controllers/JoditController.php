<?php

namespace app\controllers;

class JoditController extends \yii\web\Controller
{
    public function actionAddFavorites()
    {
        return $this->render('add-favorites');
    }

    public function actionRemoveFavorites()
    {
        return $this->render('remove-favorites');
    }

}
