<?php

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="row" style="margin-bottom: 15px">
    <div class="col-md-12">

        <?= $this->render('_buttons', [
            'model' => $model
        ]); ?>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <!-- /.box-tools -->
            <!-- /.box-header -->
            <div class="box-body">
                <?=$this->render('_form', ['model' => $model,'tags' => $tags])?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
