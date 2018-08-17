<?php

use app\widgets\AttachmentsTableWIthoutDelete;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Category;
use kartik\tree\TreeViewInput;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\select2\Select2;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>



    <?php $form = ActiveForm::begin(['enableClientValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ],]); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="form-group post-category_id">
            <label class="control-label" for="post-category_id">Категория</label>
           <?= Html::error($model, 'category_id', ['style' => 'color:#dd4b39']) ?>

        <?php
            echo TreeViewInput::widget([
                // single query fetch to render the tree
                'query'             => Category::find()->addOrderBy('root, lft'),
                'headingOptions'    => ['label' => 'Категория'],
                'name'              => 'Post[category_id]',    // input name
                'value'             => $model->category_id,         // values selected (comma separated for multiple select)
                'asDropdown'        => true,            // will render the tree input widget as a dropdown.
                'multiple'          => false,            // set to false if you do not need multiple selection
                'fontAwesome'       => true,            // render font awesome icons
                'rootOptions'       => [
                    'label' => '<i class="fa fa-tree"></i>',
                    'class'=>'required'
                ],
                'headerTemplate' =>
                    '<div class="row">           
                        <div class="col-sm-12">
                            {search}
                        </div>
                    </div>'
                // custom root label
                //'options'         => ['disabled' => true],
            ]);

            ?>

        </div>
        <?php
        $model->form_tags = ArrayHelper::map(ArrayHelper::toArray($model->tags, ['id' => 'id']),'id','id');

        echo $form->field($model, 'form_tags')->widget(Select2::classname(), [
            'data' => $tags,
           // 'value' => $model->form_tags,
            'options' => [
                    'multiple' => true,
                    'placeholder' => 'Выберите теги ...'],
            'pluginOptions' => [
                'multiple' => true,
                'maintainOrder' => true
            ],
        ]);

        ?>

       <?php  $form->field($model, 'description')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',['height' => 500]),
        ]);
       ?>
       



        <style>
            .file-preview-image{
                width:auto;height:auto; max-width: 100%;; max-height: 100%;
            }
            .file-drag-handle, .kv-file-upload{
                display: none;
            }

        </style>
        <?= \nemmo\attachments\components\AttachmentsInput::widget([
            'id' => 'file-input', // Optional
            'model' => $model,

            'options' => [ // Options of the Kartik's FileInput widget
                'multiple' => true, // If you want to allow multiple upload, default to false
            ],
            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                'overwriteInitial'      => false,
            ]
        ]) ?>



       <?php

    ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>

