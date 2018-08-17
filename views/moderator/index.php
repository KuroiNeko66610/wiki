<?php

use app\models\Post;
use app\widgets\StatusColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Свои статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Создать', ['post/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [   'attribute' => 'category_id',
                        'value' => 'category.name',
                        'headerOptions' => ['style' => 'width:15%'],
                    ] ,
                    [
                        'attribute' => 'user_id',
                        'format' => 'html',
                        'value' => function ($model){
                            return Html::a(Html::encode($model->user->username),['user/view', 'id' => $model->user_id]);
                        },
                        'headerOptions' => ['style' => 'width:10%'],
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'html',
                        'headerOptions' => ['style' => 'width:25%'],
                        'value' => function ($model){
                            return Html::a(Html::encode($model->title),['post/view', 'id' => $model->id]);
                        },
                    ],
                    [
                        'attribute' => 'description',
                        'format' => 'html',
                        'headerOptions' => ['style' => 'width:35%'],
                        'value' => function ($model) {
                            return \yii\helpers\StringHelper::truncate($model->description,100,'...');
                        },
                    ],
                 /*   [
                        'attribute' => 'status',
                        'class' => StatusColumn::class,
                        'filter' => false
                    ],
                    */
                    [   'class' => ActionColumn::class,
                        'template'=>'{view}{update}{delete}',
                        'controller' => 'post',
                        'headerOptions' => ['style' => 'width:60px'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
