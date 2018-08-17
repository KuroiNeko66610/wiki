<?php use app\models\Post;
use yii\helpers\Html;

?>

<div class="post-view box box-primary" style="margin-top: 20px">
        <div class="box-header">
<?php if( Yii::$app->user->can('moderator')):?>
    <?php if($model->status === Post::STATUS_PENDING):?>
        <?= Html::a('Одобрить', ['moderator/approve', 'id' => $model->id], [
            'class' => 'btn btn-success btn-flat',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    <?php endif;?>
    <?php if(in_array($model->status, [Post::STATUS_APPROVED, Post::STATUS_PENDING])):?>
        <?= Html::a('Отклонить', ['moderator/reject', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    <?php endif;?>
<?php endif;?>

<?php if( Yii::$app->user->can('UpdateModel', ['model' => $model])):?>
        <?php if(Yii::$app->controller->action->id != 'update' and in_array($model->status, [Post::STATUS_DRAFT, Post::STATUS_REJECTED])):?>
        <?= Html::a('Отправить на модерацию', ['moderator/pending', 'id' => $model->id], [
                    'class' => 'btn btn-warning btn-flat',
                    'data' => [
                        'method' => 'post',
                    ],
                ]) ?>
        <?php endif;?>

            <?php if(Yii::$app->controller->action->id == 'view'):?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php else: ?>
                <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php endif;?>

            <?php if($model->status != Post::STATUS_APPROVED or  Yii::$app->user->can('moderator')):?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить статью?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif;?>

<?php elseif(Yii::$app->controller->action->id != 'update' and $model->status == Post::STATUS_APPROVED):?>
    <?= Html::a('Дополнить', ['correction/create', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
<?php endif; ?>

    </div>
</div>
