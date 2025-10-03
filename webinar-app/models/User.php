<?php
namespace app\models;

use yii\web\IdentityInterface;

class User implements IdentityInterface
{
    public $id;
    public $username;
    public $password;

    private static $users = [
        'admin' => ['id' => 1, 'username' => 'admin', 'password' => 'admin123'],
    ];

    public static function findIdentity($id)
    {
        foreach (self::$users as $user) {
            if ($user['id'] === $id) {
                return new self($user);
            }
        }
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return isset(self::$users[$username]) ? new self(self::$users[$username]) : null;
    }

    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
}
