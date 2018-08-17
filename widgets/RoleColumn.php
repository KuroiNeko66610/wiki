<?php

namespace app\widgets;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\rbac\Item;

class RoleColumn extends DataColumn
{
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'author';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';

    protected function renderDataCellContent($model, $key, $index)
    {
        $roles = Yii::$app->authManager->getRolesByUser($model->id);
        return $roles === [] ? $this->grid->emptyCell : implode(', ', array_map(function (Item $role) {
            return $this->getRoleLabel($role);
        }, $roles));
    }

    private function getRoleLabel(Item $role)
    {
        //$class = $role->name == Self::ROLE_USER ? 'primary' : 'danger';
        switch ($role->name){
            case self::ROLE_GUEST :
                $class = 'default';
                break;
            case self::ROLE_USER :
                $class = 'primary';
                break;
            case self::ROLE_MODERATOR :
                $class = 'warning';
                break;
            case self::ROLE_ADMIN :
                $class = 'success';
                break;
        }

        return Html::tag('span', Html::encode($role->description), ['class' => 'label label-' . $class]);
    }
}