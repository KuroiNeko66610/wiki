<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJsFile('@web/js/synctranslit/jquery.synctranslit.min.js',  ['depends' => [\app\assets\AppAsset::className()]]);
$this->registerJsFile('@web/js/synctranslit/use.js',  ['depends' => [\app\assets\AppAsset::className()]]);
?>
<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'options' => ['id' => 'tag-name']]) ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'options' => ['id' => 'tag-slug']]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
