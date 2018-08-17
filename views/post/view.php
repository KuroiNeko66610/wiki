<?php

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_buttons', [
    'model' => $model
]); ?>


<?php
echo $this->render('_post', [
    'model' => $model
]);
?>

