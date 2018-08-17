<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserEditForm extends Model
{
    public $email;
    public $phone;
    public $role;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->email = $user->email;
        $this->phone = $user->phone;
        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['role'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['phone', 'integer'],
        ];
    }

    public function rolesList()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}