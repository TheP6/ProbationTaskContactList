<?php

namespace app\models\input\contacts;

use app\models\entity\Contact;
use yii\base\Model;
use app\models\entity\User;

/**
 * CreateContactForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class CreateContactForm extends Model
{
    public $name;
    public $surname;
    public $patronymic;

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
            [['name'], 'required'],
            ['name', 'string', 'min' => 2],
            ['name', 'nameIsUnique'],
            ['surname', 'string', 'min' => 2],
            ['patronymic', 'string', 'min' => 2],
        ];
    }

    public function setCurrentUser(User $user): CreateContactForm
    {
        $this->_user = $user;

        return $this;
    }

    public function nameIsUnique()
    {
        if (!$this->hasErrors()) {

            $contact = Contact::find()
                ->where(['name' => $this->name])
                ->andWhere(['surname' => $this->surname])
                ->andWhere(['patronymic' => $this->patronymic])
                ->andWhere(['user_id' => $this->_user->id()])
                ->one();

            if (null !== $contact) {
                $this->addError('name', 'Name is already taken!');
            }
        }
    }
}
