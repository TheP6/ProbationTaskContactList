<?php

namespace app\models\entity;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class Phone
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public function id(): int
    {
        return $this->id;
    }

    public function login(): string
    {
        return $this->login;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function phoneBook(): array
    {
        return $this->contacts;
    }

    public function setPasswordHash(string $hash): User
    {
        $this->passwordHash = $hash;
        return $this;
    }

    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


}