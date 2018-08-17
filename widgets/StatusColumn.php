<?php

namespace app\widgets;

use yii\grid\DataColumn;
use yii\helpers\Html;

class StatusColumn extends DataColumn
{
    const STATUS_DRAFT = 10;
    const STATUS_PENDING = 20;
    const STATUS_APPROVED = 30;
    const STATUS_REJECTED = 40;

    protected function renderDataCellContent($model, $key, $index)
    {
         return $this->getStatusLabel($model);
    }

    private function getStatusLabel($model)
    {
        $class = '';
        $label = '';
        switch ($model->status){
            case self::STATUS_DRAFT :
                $class = "default";
                $label = 'черновик';
                break;
            case self::STATUS_APPROVED :
                $class = "success";
                $label = 'одобрено';
                break;
            case self::STATUS_PENDING :
                $class = "warning";
                $label = 'на модерации';
                break;
            case self::STATUS_REJECTED :
                $class = "danger";
                $label = 'отклонено';
                break;
        }

        return Html::tag('span', Html::encode($label), ['class' => 'label label-' . $class]);
    }
}