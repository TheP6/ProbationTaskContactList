<?php

namespace app\models\input\contacts;

use app\models\entity\Contact;
use yii\base\Model;
use app\models\entity\User;

/**
 * PatchContactForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PatchContactForm extends Model
{
    public $name;
    public $surname;
    public $patronymic;

    /**
     * @var User
     */
    public $_user;

    /**
     * @var Contact
     */
    public $_currentContact;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['name', 'string', 'min' => 2],
            ['name', 'nameIsUnique'],
            ['surname', 'string', 'min' => 2],
            ['surname', 'nameIsUnique', 'when' => function($model) {
                return $model->name === null;
            }],
            ['patronymic', 'string', 'min' => 2],
            ['patronymic', 'nameIsUnique', 'nameIsUnique', 'when' => function($model) {
                return $model->name === null && $model->surname === null;
            }],
        ];
    }

    public function setCurrentUser(User $user): PatchContactForm
    {
        $this->_user = $user;

        return $this;
    }

    public function setCurrentContact(Contact $contact): PatchContactForm
    {
        $this->_currentContact = $contact;

        return $this;
    }

    public function nameIsUnique()
    {
        if (!$this->hasErrors()) {

            $contact = Contact::find()
                ->where([
                    'name' => $this->name ?? $this->_currentContact->name()
                ])
                ->andWhere([
                    'surname' => $this->surname ?? $this->_currentContact->surname()
                ])
                ->andWhere([
                    'patronymic' => $this->patronymic ?? $this->_currentContact->patronymic()
                ])
                ->andWhere([
                    'user_id' => $this->_user->id()
                ])
                ->andWhere(['!=', 'id', $this->_currentContact->id()])
                ->one();

            if (null !== $contact) {
                $this->addError('name', 'Name is already taken!');
            }
        }
    }
}
