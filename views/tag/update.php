<?php

/* @var $this yii\web\View */
/* @var $tag app\models\Tag */

$this->title = $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->name, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $tag,
    ]) ?>

</div>
