<?php

use app\widgets\StatusColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CorrectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Модерация дополнений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">


    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
               // 'filterModel' => $searchModel,
                'columns' => [
                    [   'attribute' => 'post_id',
                        'format' => 'raw',
                        'value' => function ($model){
                            return Html::a(Html::encode($model->post->title),['post/view', 'id' => $model->post_id]);
                        },

                    ],
                    [   'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function ($model){
                            return Html::a(Html::encode($model->title),['view', 'id' => $model->id]);
                        },

                    ],
                    [
                        'attribute' => 'user_id',
                        //'value' => '',
                        'format' => 'html',
                        'value' => function ($model){
                            return Html::a(Html::encode($model->user->username),['user/view', 'id' => $model->user_id]);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'class' => StatusColumn::class,
                        'filter' => $searchModel->statusList(),
                    ],
                    //'status',
                    //'created_at',
                    //'updated_at',                    
                ],
            ]); ?>
        </div>
    </div>
</div>
