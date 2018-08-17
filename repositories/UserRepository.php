<?php

namespace app\repositories;

use app\models\User;

class UserRepository
{

    public function findByUsername($value)
    {
        return User::find()->andWhere(['or', ['username' => $value]])->one();
    }

    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }
    /**
     * @param User $user
     */
    public function save(User $user)
    {
        if (!$user->save())
            throw new \RuntimeException('Saving error.');
    }



    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        if (!$user->delete())
            throw new \RuntimeException('Removing error.');
    }


    /**
     * @param array $condition
     * @return array|null|\yii\db\ActiveRecord
     */
    private function getBy(array $condition)
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new \RuntimeException('User not found.');
        }
        return $user;
    }
}