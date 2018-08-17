<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use app\models\Category;
use kartik\widgets\Typeahead;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-header with-border">
    <?= Html::a('Создать', ['post/create'], ['class' => 'btn btn-success btn-flat']) ?>
</div>

<div class="box" >

        <?php
        echo \kartik\tree\TreeViewInput::widget([
            // single query fetch to render the tree
            'query'             => Category::find()->addOrderBy('root, lft'),
            'id' => 'treeID',
            'value' => Yii::$app->request->get('id'),
            'name' => 'Categories',

            'dropdownConfig' =>[
                    'input' => [
                        'placeholder' => 'Выберите категорию для просмотра',
                    ]
            ],
            //'value'             => $model->category_id,         // values selected (comma separated for multiple select)
            'asDropdown'        => true,            // will render the tree input widget as a dropdown.
            'multiple'          => false,            // set to false if you do not need multiple selection
            'fontAwesome'       => true,            // render font awesome icons
            'rootOptions'       => [
                'label' => '<i class="fa fa-tree"></i>',
                'class'=>'text-success'
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

<style>
    .example-twitter-oss .tt-suggestion {
        padding: 8px 20px;
    }
    .example-twitter-oss .tt-suggestion + .tt-suggestion {
        border-top: 1px solid #CCCCCC;
    }
    .example-twitter-oss .repo-language {
        float: right;
        font-style: italic;
    }
    .example-twitter-oss .repo-name {
        font-weight: bold;
    }
    .example-twitter-oss .repo-description {
        font-size: 12px;
        padding: 5px 0;
        overflow: hidden;
    }
    .example-sports .league-name {
        border-bottom: 1px solid #CCCCCC;
        margin: 0 20px 5px;
        padding: 10px;
    }
</style>


<div id = "post-content">
    <?php
    foreach ($data_provider->models as $model) {
        echo $this->render('_post', [
            'model' => $model,
            'truncate' => true
        ]);
    }
    ?> 
</div>
   <?php echo LinkPager::widget([
        'pagination' => $data_provider->pagination,
    ]);?>