<?php

namespace app\models\input\phones;

use app\models\entity\Contact;
use app\models\entity\Phone;
use yii\base\Model;
use app\models\entity\User;

/**
 * CreatePhoneForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class CreatePhoneForm extends Model
{
    public $contact_id;

    public $phone;

    public $label;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @var Contact
     */
    protected $_contact;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone', 'contact_id'], 'required'],
            ['label', 'string', 'max' => 15],
            ['contact_id', 'number'],
            ['contact_id', 'contactExists'],
            ['phone', 'phoneIsUnique'],
            ['phone', 'phoneIsValid'],
        ];
    }

    public function setCurrentUser(User $user): CreatePhoneForm
    {
        $this->_user = $user;

        return $this;
    }

    public function getContact(): ?Contact
    {
        if (null === $this->_contact && null !== $this->contact_id) {
            $this->_contact = Contact::find()
                    ->where(['id' => $this->contact_id])
                    ->andWhere(['user_id' => $this->_user->id()])
                    ->one();
        }

        return $this->_contact;
    }

    public function contactExists()
    {
        if (null === $this->getContact()) {
            $this->addError('contact_id', 'Contact does not exist!');
        }
    }

    public function phoneIsValid()
    {
        $result = preg_match('/^\+[1-9]{1}[0-9]{3,14}$/', $this->phone);

        if ($result === FALSE) {
            throw new \Exception("Preg match failed!");
        }

        if ($result === 0) {
            $this->addError('phone', 'Phone is invalid!');
        }
    }

    public function phoneIsUnique()
    {
        if (!$this->hasErrors()) {

            $phoneTable = Phone::tableName();
            $contactTable = Contact::tableName();

            $phone = Phone::find()
                ->select("{$phoneTable}.*")
                ->innerJoin(
                    $contactTable,
                    "`{$phoneTable}`.`contact_id` = `{$contactTable}`.`id`"
                )
                ->where(["`{$contactTable}`.`user_id`" => $this->_user->id()])
                ->andWhere(["`{$phoneTable}`.`number`" => $this->phone])
                ->one();

            if (null !== $phone) {
                $this->addError('phone', 'Phone is already present!');
            }
        }
    }
}
