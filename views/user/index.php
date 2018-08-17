<?php

use app\models\Tag;
use app\widgets\RoleColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'username',
                        'format' => 'html',
                        'value' => function ($model){
                            return Html::a(Html::encode($model->username),['user/view', 'id' => $model->id]);
                        },
                    ],
                    'email',
                    'phone',
                    [
                        'attribute' => 'role',
                        'class' => RoleColumn::class,
                        'filter' => $searchModel->rolesList(),
                    ],
                    ['class' => ActionColumn::class,
                    'headerOptions' => ['style' => 'width:70px'],
                    ],

                ],
            ]); ?>
        </div>
    </div>
</div>
