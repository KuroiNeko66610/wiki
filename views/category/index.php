<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tree\TreeView;/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-tree-index box box-primary">

    <?php
    echo TreeView::widget([
    // single query fetch to render the tree
    'query'             => $query,
    'headingOptions'    => ['label' => 'Категории'],
    'isAdmin'           => false,                       // optional (toggle to enable admin mode)
        'softDelete' => false,
    'displayValue'      => 1,                           // initial display value
        'showIDAttribute' => false,
        'allowNewRoots' => true,
        'hideTopRoot' => true,
        'iconEditSettings'=> [
            'show' => 'text',
        ],
        'mainTemplate' => '
        <div class="row">
            <div class="col-sm-7">
                {wrapper}
            </div>
            <div class="col-sm-5">
                {detail}
            </div>
        </div>',

        'headerTemplate' =>
        '<div class="row">           
            <div class="col-sm-12">
                {search}
            </div>
        </div>'
        //'softDelete'      => true,                        // normally not needed to change
    //'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
    ]);
    ?>

</div>

