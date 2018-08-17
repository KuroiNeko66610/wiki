<?php

use app\models\Category;
use kartik\select2\Select2;
use kartik\tree\TreeViewInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\search\PostSearch */
/* @var $form ActiveForm */
?>
<?php Pjax::begin()?>
<div class="search">
    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo TreeViewInput::widget([
        // single query fetch to render the tree
        'query'             => Category::find()->addOrderBy('root, lft'),
        'headingOptions'    => ['label' => 'Категория'],
        'name'              => 'PostSearch[category_id]',    // input name
        'value'             => $model->category_id,         // values selected (comma separated for multiple select)
        'asDropdown'        => true,            // will render the tree input widget as a dropdown.
        'multiple'          => true,            // set to false if you do not need multiple selection
        'fontAwesome'       => true,            // render font awesome icons
        'dropdownConfig' => ['input' => ['placeholder' => 'Категория']],
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


    <?php
    //var_dump($tags);
   // $model->form_tags = ArrayHelper::map(ArrayHelper::toArray($tags, ['id' => 'id']),'id','id');

    echo $form->field($model, 'form_tags')->widget(Select2::classname(), [
        'data' => $tags,
        'options' => [
            'multiple' => true,
            'placeholder' => 'Теги'],
        'pluginOptions' => [
            'multiple' => true,
            'maintainOrder' => true
        ],
    ]);

    ?>

    <?= $form->field($model, 'text')->textarea(['placeholder' => 'Строка поиска'])->label(false);?>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end()?>
