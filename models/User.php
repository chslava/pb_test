<?php

namespace app\models;

use app\models\db\DBFile;
use Yii;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $users = DBFile::getUsers();
        $user = array_pop(array_filter($users, function ($e) use($id) {return $e->id == $id;}));
        if (is_a($user, 'SimpleXMLElement')) $user = get_object_vars($user);
        return is_array($user) ? new static($user) : null;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = DBFile::getUsers();
        $user = array_pop(array_filter($users, function ($e) use($token) {return $e->accessToken == $token;}));
        if (is_a($user, 'SimpleXMLElement')) $user = get_object_vars($user);
        return is_array($user) ? new static($user) : null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $users = DBFile::getUsers();
        foreach ($users as $user) {
            if(strcasecmp((string)$user->username, $username) === 0){
                $userData = get_object_vars($user);
                return new static($userData);
            }
        }

        self::checkAttempt();

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if($this->password === $password) {
            self::checkAttempt(true);
            return true;
        }
        self::checkAttempt();
        return false;
    }

    protected static function checkAttempt($reset = false){
        if ($reset) {
            Yii::$app->session['login_attempts'] = null;
            return true;
        }
        $attempts = Yii::$app->session['login_attempts'];
        if (is_array($attempts)) {
            $attempts['attempt']++;
            Yii::$app->session['login_attempts'] = $attempts;
        } else {
            Yii::$app->session['login_attempts'] = ['attempt' => 2, 'timestamp' => time()];
        }
        return true;
    }
}
