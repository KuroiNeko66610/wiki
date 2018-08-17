
<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = $user->username;

$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <?php if( Yii::$app->user->can('admin')):?>
        <p>
            <?= Html::a('Изменить', ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $user->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif;?>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $user,
                'attributes' => [
                    'username',
                    'email:email',
                    'phone',
                    [
                        'label' => 'Role',
                        'value' => implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user->id), 'description')),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>


<div id = "post-content">
    <?php Pjax::begin(); ?>
    <?php
    foreach ($posts->models as $model) {
        echo $this->render('../post/_post', [
            'model' => $model,
            'truncate' => true
        ]);
    }
    ?>
    <?php echo LinkPager::widget([
        'pagination' => $posts->pagination,
    ]);?>
    <?php Pjax::end(); ?>
</div>
