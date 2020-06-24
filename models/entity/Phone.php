<?php

namespace app\models\entity;

use yii\db\ActiveRecord;

/**
 * Class Phone
 */
class Phone extends ActiveRecord
{
    public static function tableName()
    {
        return 'phones';
    }

    public function id(): int
    {
        return $this->id;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function contact(): Contact
    {
        return $this->contact;
    }

    public function getContact()
    {
        return $this->hasOne(Contact::class, ['id' => 'contact_id']);
    }

    public function setNumber(string $number): Phone
    {
        $this->number = $number;

        return $this;
    }

    public function setLabel(?string $label): Phone
    {
        $this->label = $label;

        return $this;
    }

    public function setContact(Contact $contact): Phone
    {
        $this->contact_id = $contact->id();

        return $this;
    }
}