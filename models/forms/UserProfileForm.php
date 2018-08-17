<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class UserProfileForm extends Model
{
    public $phone;
    public $email;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email','phone'], 'trim'],
            ['phone', 'match', 'pattern' => '/^[\d-, \(\)]+$/', 'message' => ' Допустимо использовние цифр, () и -' ],
            ['email', 'email']
        ];


    }
}
