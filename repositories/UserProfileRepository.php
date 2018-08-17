<?php

namespace app\repositories;

use app\models\UserProfile;

class UserProfileRepository
{

    public function getByUserId($id)
    {
        return $this->getBy(['user_id' => $id]);
    }

    public function save(UserProfile $profile)
    {
        if (!$profile->save())
            throw new \RuntimeException('Saving error.');
    }


    public function remove(UserProfile $profile)
    {
        if (!$profile->delete())
            throw new \RuntimeException('Removing error.');
    }

    private function getBy(array $condition)
    {
        if (!$post = UserProfile::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('UserProfile not found.');
        }
        return $post;
    }
}