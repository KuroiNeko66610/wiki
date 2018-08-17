<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Correction */

$this->title = 'Update Correction: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Corrections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="correction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
