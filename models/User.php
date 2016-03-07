<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    //ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    //ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() {return date('U'); } // Unix timestamp
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /*
    * Autoupdate created_at and updated_at fields.
    */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert)
                $this->created_at = date('U');
            $this->updated_at = date('U');
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds if there are any admins in the blog
     * @return bool
     */
    public function alreadyHasAdmins()
    {
        $auth = Yii::$app->authManager;
        $getAlreadyHasAdmins = $auth->getUserIdsByRole('admin');
        return empty($getAlreadyHasAdmins) ? $alreadyHasAdmins = false : $alreadyHasAdmins = true;
    }

    /**
     * Finds if current user is an admin(haves an admin rights)
     * @return bool
     */
    public function isAdmin()
    {
        $auth = Yii::$app->authManager;
        $getIsAdmin = $auth->getRolesByUser(Yii::$app->user->id);
        return (ArrayHelper::getValue($getIsAdmin, 'admin')) ? true : false;
    }

    /**
     * Gets a role of selected user
     * @param $id| ID of selected user
     * @return string Role of the user
     */
    public function getRole($id)
    {
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole('author');
        $adminRole = $auth->getRole('admin');
        $getRoles = $auth->getRolesByUser($id);
        if(ArrayHelper::getValue($getRoles, 'author'))
            return 'Author';
        else if(ArrayHelper::getValue($getRoles, 'admin'))
            return 'Administrator';
    }

    /**
     * Sets a role to selected user
     * @param $id| ID of selected user
     * @param $role
     */
    public function setRole($id, $role)
    {
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole('author');
        $adminRole = $auth->getRole('admin');
        $getRoles = $auth->getRolesByUser($id);
        if($role != null) {
            if($role == 'admin') {
                if(ArrayHelper::getValue($getRoles, 'author'))
                    $auth->revoke($authorRole, $id);
                if(ArrayHelper::getValue($getRoles, 'admin') == null)
                    $auth->assign($adminRole, $id);
            } elseif($role == 'author') {
                if(ArrayHelper::getValue($getRoles, 'admin'))
                    $auth->revoke($adminRole, $id);
                if(ArrayHelper::getValue($getRoles, 'author') == null)
                    $auth->assign($authorRole, $id);
            }
        }
    }

    /**
     * Finds all users where 'id' IN (string).
     * @param $author_IDs| String contains all user ID's
     * @return array|\yii\db\ActiveRecord[] Returns an array contains info about all users
     */
    public function findByAuthorIDs ($author_IDs)
    {
        if($author_IDs) {
            $query = User::find()
                ->select('id, username')
                ->where('id IN(' . $author_IDs . ')')
                ->asArray()
                ->all();
            return $query;
        } else {
            return false;
        }
    }

    /**
     * Searches username by user ID
     * @param $array| All users that will be displayed
     * @param $id| ID of selected user
     * @return string| string Username of selected user
     */
    public function searchUsernameById($array, $id)
    {
        $i = 0;
        do
            if($array[$i]['id'] == $id)
                return $array[$i]['username'];
        while(++$i < count($array));
    }

    /**
     * Finds identity by user id, his status must be active
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Find identity by access token is not must to be implemented
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username, his status must be active
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password by his hash
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Checks user privilegies for update/delete users or own user info
     * @return bool
     */
    public function checkUDPrivilegies()
    {
        return ((Yii::$app->user->can('updateOwnUser', ['user' => new User]) || Yii::$app->user->can('updateUser'))
            && (Yii::$app->user->can('deleteOwnUser', ['user' => new User]) || Yii::$app->user->can('deleteUser')));
    }
}
