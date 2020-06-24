<?php

namespace app\models\input\contacts;

use app\models\entity\User;

/**
 * PatchPhoneForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PatchPhoneForm extends CreatePhoneForm
{
    public $phone;

    public $label;

    /**
     * @var User
     */
    public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['label', 'string', 'max' => 15],
            ['contact_id', 'number'],
            ['contact_id', 'contactExists'],
            ['phone', 'phoneIsUnique'],
            ['phone', 'phoneIsValid'],
        ];
    }
}
