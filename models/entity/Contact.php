<?php

namespace app\models\entity;

use yii\db\ActiveRecord;

/**
 * Class Contact
 */
class Contact extends ActiveRecord
{
    public static function tableName()
    {
        return 'contacts';
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function patronymic(): string
    {
        return $this->patronymic;
    }

    public function phones(): array
    {
        return $this->phones;
    }

    public function getPhones()
    {
        return $this->hasMany(Phone::class, ['order_id' => 'id']);
    }

    public function user(): User
    {
        return $this->user;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function setName(string $name): Contact {
        $this->name = $name;

        return $this;
    }

    public function setSurname(string $surname): Contact {
        $this->surname = $surname;

        return $this;
    }

    public function setPatronymic(string $patronymic): Contact
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function setUser(User $user): Contact
    {
        $this->user_id = $user->id();
        return $this;
    }
}