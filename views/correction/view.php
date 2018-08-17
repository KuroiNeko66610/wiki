<?php

use app\models\Correction;
use yii\helpers\Html;
use Caxy\HtmlDiff\HtmlDiff;
use Caxy\HtmlDiff\HtmlDiffConfig;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
use yii\gii\components\DiffRendererHtmlInline;

/* @var $this yii\web\View */
/* @var $model app\models\Correction */

$this->title = "Дополнение к статье: ". $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Дополнения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$status = '';
switch ($correction->status){
    case Correction::STATUS_APPROVED :
        $status = "box-success";
        break;
    case Correction::STATUS_PENDING :
        $status = "box-warning";
        break;
    case Correction::STATUS_REJECTED :
        $status = "box-danger";
        break;
}
?>
<style>
    .box-post .breadcrumb{
        margin-bottom: auto;
    }
    .label a{
        color: white;
    }
    .collapse-button{
        margin-bottom: -20px;
    }

    ins {
        color: #333;
        background-color: limegreen;
        text-decoration: none;
    }
    del{
        color: #a33;
        background-color: #ffeaea;
        text-decoration: line-through;
    }



</style>

<?php ?>
<p>
<?php if( Yii::$app->user->can('moderator') and in_array($correction->status, [Correction::STATUS_PENDING])):?>
    <!--<?= Html::a('Одобрить', ['approve', 'id' => $correction->id], ['class' => 'btn btn-success']) ?> -->
    <?= Html::a('Редактировать статью', ['post/update', 'id' => $correction->post_id], ['target' => '_blank','class' => 'btn btn-warning']) ?>
    <?= Html::a('Одобрить', ['update-post', 'id' => $correction->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Отклонить', ['reject', 'id' => $correction->id], ['class' => 'btn btn-danger']) ?>
<?php endif;?>

</p>

<div class="box box-post <?=$status?>">
    <div class=" pull-right collapse-button">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
    <ul class="breadcrumb">
        <li><strong>Текст дополнения</strong></li>
    </ul>
    <div class="box-header with-border">
        <h4 class="box-title"><?=Html::a($correction->title,['post/view', 'id' => $correction->id])?></h4>

        <div class="box-tools pull-right">
            <label style="margin-left: 10px"><?=Html::a($correction->user->username,['user/view', 'id' => $correction->user_id])?></label>

        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <?=$correction->text?>
    </div>
    <!-- /.box-body -->
</div>

<div class="box box-post">
    <div class="pull-right collapse-button">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
    <ul class="breadcrumb">
        <li><strong>Изменения</strong></li>
    </ul>
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php
        $firstHtmlDiff = HtmlDiff::create($post->description, $correction->text);
        $firstContent = $firstHtmlDiff->build();
        echo $firstContent;
        ?>
    </div>
    <!-- /.box-body -->
</div>


