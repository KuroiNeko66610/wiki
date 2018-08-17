<?php
/**
 * Created by PhpStorm.
 * User: Neko
 * Date: 16.01.2018
 * Time: 8:15
 */



    foreach ($model->models as $model) {
        echo $this->render('../post/_post', [
            'model' => $model
        ]);
    }


    ?>