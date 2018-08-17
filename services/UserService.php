<?php

namespace app\services;

use app\models\forms\UserEditForm;
use app\models\forms\UserProfileForm;
use app\repositories\UserRepository;
use Yii;

class UserService
{
    private $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function get($id){
        return $this->user->get($id);
    }

    public function edit($id, UserEditForm $form)
    {
        $user = $this->user->get($id);
        $user->edit(
            $form->phone,
            $form->email
        );

        $this->assignRole($id, $form->role);
        $this->user->save($user);
    }

    public function updateProfile($id, UserProfileForm $form)
    {
        $user = $this->user->get($id);
        $user->edit(
            $form->phone,
            $form->email
        );
        $this->user->save($user);
    }

    public function remove($id)
    {
        $user = $this->user->get($id);
        $this->user->remove($user);
    }

    private function assignRole($user_id, $name)
    {
        $am = $auth = Yii::$app->authManager;

        if (!$role = $am->getRole($name)) {
            throw new \DomainException('Role "' . $name . '" does not exist.');
        }
        $roles = $am->getRolesByUser($user_id);

        if(!in_array($role, $roles)){
            $am->revokeAll($user_id);
            $am->assign($role, $user_id);
        }

    }
}