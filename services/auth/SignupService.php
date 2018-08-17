<?php
/**
 * Created by PhpStorm.
 * User: Neko
 * Date: 13.01.2018
 * Time: 13:46
 */

namespace app\services\auth;

use app\models\forms\auth\SignupForm;
use app\models\User;
use app\repositories\UserRepository;
use SebastianBergmann\GlobalState\RuntimeException;

class SignupService
{

    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    /**
     * @param SignupForm $form
     * @return User|null
     */

}