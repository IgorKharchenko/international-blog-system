<?php
namespace app\models;

use yii\helpers\ArrayHelper;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->created_at = time();
        $user->last_login = time();
        $user->status = 10;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->save();

        $auth = Yii::$app->authManager;
        $adminRole = $auth->getRole('admin');
        $authorRole = $auth->getRole('author');
        $getRoles = $auth->getRolesByUser($user->getId());
        if (!is_null($getRoles)) {
            if (ArrayHelper::getValue($getRoles, 'admin'))
                $auth->revoke($adminRole, $user->getId());
            if (ArrayHelper::getValue($getRoles, 'author') == null)
                $auth->assign($authorRole, $user->getId());
        }
        return $user;
    }
}
