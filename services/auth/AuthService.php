<?php
/**
 * Created by PhpStorm.
 * User: Neko
 * Date: 13.01.2018
 * Time: 13:46
 */

namespace app\services\auth;

use app\models\forms\auth\LoginForm;
use app\models\forms\auth\SignupForm;
use app\models\User;
use app\models\UserProfile;
use app\repositories\NotFoundException;
use app\repositories\UserRepository;
use app\services\UserProfileService;
use app\widgets\VDump;
use SebastianBergmann\GlobalState\RuntimeException;
use Yii;
use yii\helpers\VarDumper;

class AuthService
{

    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }


    /**
     * @param LoginForm $form
     * @return array|null|\yii\db\ActiveRecord
     */
    public function auth(LoginForm $form)
    {
        $user = $this->users->findByUsername($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new NotFoundException('Undefined user or password.');
        }
        return $user;
    }

    public function signup(SignupForm $form)
    {

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $user = User::signup($form->username, $form->password);

            $this->users->save($user);

            $profile= new UserProfile();
            $profile->link('user',$user);
            //$saved = $this->users->get($user->id);
            //VDump::vd($saved);

            $am = $auth = Yii::$app->authManager;
            $role = $am->getRole('author');
            $am->assign($role, $user->id);

            $transaction->commit();

            return  $user;

        } catch (\Exception $e) {

            $transaction->rollBack();

        }

        /*if(!$user->save())
            throw new RuntimeException('Ошибка сохранения');

        $profile = UserProfile::create($user->id);
*/

    }
}