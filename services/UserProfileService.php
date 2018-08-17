<?php

namespace app\services;

use app\models\forms\UserEditForm;
use app\models\forms\UserProfileForm;
use app\models\UserProfile;
use app\repositories\UserProfileRepository;
use app\repositories\UserRepository;
use Yii;

class UserProfileService{
    private $profile;

    public function __construct(UserProfileRepository $profile)
    {
        $this->profile = $profile;
    }

    public function addToFavorites(UserProfile $profile, $post_id){

        if(empty($profile->favorites))
            $profile->favorites = $post_id;
        else
            $profile->favorites .= ','.$post_id ;

        $this->profile->save($profile);
    }

    public function RemoveFavorites(UserProfile $profile, $post_id){
        $favorites = explode(',',$profile->favorites);
        $favorites = array_unique($favorites);
        $favorites = array_diff($favorites, array($post_id));
        $favorites = implode(',',$favorites);

        $profile->favorites = $favorites;
        $this->profile->save($profile);
    }

    public static function isInFavorites(UserProfile $profile, $post_id){
        $favorites = explode(',',$profile->favorites);

        return in_array($post_id,$favorites);

    }
}