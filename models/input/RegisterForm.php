<?php

namespace app\models\input;

use app\models\entity\User;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public $name;
    public $password;
    public $password_repeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 5 ],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['name', 'validateUniqueUser'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateUniqueUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne([
                'login' => $this->name
            ]);

            if (null !== $user) {
                $this->addError($attribute, 'Username is already in use');
            }
        }
    }
}
