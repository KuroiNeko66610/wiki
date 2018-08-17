<?php use app\models\Post;
use app\services\UserProfileService;
use app\widgets\AttachmentsTableWIthoutDelete;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\Breadcrumbs;
use yii\helpers\HtmlPurifier;

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


</style>
<?php
$status = '';
switch ($model->status){
    case Post::STATUS_DRAFT :
        $status = "box-default";
        break;
    case Post::STATUS_APPROVED :
        $status = "box-success";
        break;
    case Post::STATUS_PENDING :
        $status = "box-warning";
        break;
    case Post::STATUS_REJECTED :
        $status = "box-danger";
        break;
}
?>
<div class="box box-post <?=$status?>">
    <div class=" pull-right collapse-button">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
    <?php $breadcrumbs = $model->category->getBreadcrumbsArray(1);?>
    <?=Breadcrumbs::widget(['homeLink' => false,'links' => $breadcrumbs])?>

    <div class="box-header with-border">
        <h4 class="box-title"><?=Html::a($model->title,['post/view', 'id' => $model->id])?></h4>

        <?php
        if(UserProfileService::isInFavorites(Yii::$app->user->identity->profile, $model->id))
            $fav = 'fa-star';
        else
            $fav = 'fa-star-o'
        ?>
        <button type="button" class="btn btn-box-tool"><i onclick = "favorites($(this),<?=$model->id?>)" class="fa <?=$fav?>"></i></button>
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
            <?php $model->description =  HtmlPurifier::process($model->description,['AutoFormat.RemoveEmpty.RemoveNbsp' => true,'AutoFormat.RemoveEmpty' => true,'HTML.Allowed' => 'p,a[href|rel|target|title],span[style],strong,em,ul,ol,li']);
            echo StringHelper::truncate($model->description,500,'','UTF-8',true)
            ?>
        <? else:?>
        <?=HtmlPurifier::process($model->description)?>
            <?php if(!empty($model->files)):?>
                <hr>
                <strong>Прикрепленные файлы:</strong>
                <?= AttachmentsTableWIthoutDelete::widget(['model' => $model]) ?>
            <?php endif; ?>
        <?php  endif; ?>
    </div>
    <!-- /.box-body -->
</div>
