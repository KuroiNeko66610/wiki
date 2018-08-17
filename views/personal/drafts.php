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

$this->title = 'Черновики';
$this->params['breadcrumbs'][] = ['label' => 'Личный раздел'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-header with-border">
    <?= Html::a('Создать', ['post/create'], ['class' => 'btn btn-success btn-flat']) ?>
</div>

<div class="box">

</div>

<div id = "post-content">
    <?php Pjax::begin(); ?>
    <?php
    foreach ($data_provider->models as $model) {
        echo $this->render('../post/_post', [
            'model' => $model,
            'truncate' => true
        ]);
    }
    ?>
    <?php echo LinkPager::widget([
        'pagination' => $data_provider->pagination,
    ]);?>
    <?php Pjax::end(); ?>
</div>