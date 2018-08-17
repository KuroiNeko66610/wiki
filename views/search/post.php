<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use \yii\widgets\Pjax;

?>

<?php

    echo $this->render('../post/_post', [
        'model' => $model
    ]);
?>

