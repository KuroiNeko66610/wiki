<?php

/* @var $this yii\web\View */
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $model app\models\Post */

$this->title = 'Расширенный поиск';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Расширенный поиск';
?>



<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <!-- /.box-tools -->
            <!-- /.box-header -->
            <div class="box-body">
                <?=$this->render('_form', ['model' => $searchModel,'tags' => $tags])?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<div style="clear: both"></div>

<div id = "post-content" style="padding: 10px">
    <?php Pjax::begin()?>
    <?php
    if(!empty($dataProvider))
        foreach ($dataProvider->models as $model) {
            echo $this->render('../post/_post', [
                'model' => $model,
                'truncate' => true
            ]);
        }
        ?>
    <?php Pjax::end()?>
</div>