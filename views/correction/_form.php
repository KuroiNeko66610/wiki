<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

?>

<?php Pjax::begin(['id' => 'correction_form', 'enablePushState' => false])?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<div class="box-body table-responsive">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',['height' => 500]),
    ]);
    ?>

</div>
<div class="box-footer">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php Pjax::end()?>
