<?php use yii\helpers\Html;
use yii\widgets\Breadcrumbs; ?>
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


</style>
<div class="box box-post">
    <div class=" pull-right collapse-button">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
    <?php $breadcrumbs = $model->category->getBreadcrumbsArray();?>
    <?=Breadcrumbs::widget(['homeLink' => false,'links' => $breadcrumbs])?>

    <div class="box-header with-border">
        <h4 class="box-title"><?=Html::a($model->title,['moderator/view', 'id' => $model->id])?></h4>

        <div class="box-tools pull-right">
            <?php foreach ($model->tags as $tag):?>
                <span class="label label-primary"><?=Html::a($tag->name,['post/tag','slug' => $tag->slug])?></span>
            <?php endforeach;?>
            <label style="margin-left: 10px"><?=Html::a($model->user->username,['user/view', 'id' => $model->user_id])?></label>

        </div>

        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php if(isset($truncate)):?>
            <?=\yii\helpers\StringHelper::truncate($model->description,500,'...');?>
        <?else:?>
            <?=$model->description?>
        <?php endif; ?>
    </div>
    <!-- /.box-body -->
</div>