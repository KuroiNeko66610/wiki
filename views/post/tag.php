<?php

    $this->title = $tag->name;


    foreach ($data_provider->models as $model) {
        echo $this->render('_post', [
            'model' => $model
        ]);
    }


    ?>