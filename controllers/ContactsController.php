<?php

namespace app\controllers;

use app\controllers\resources\ContactResourceTrait;
use app\models\entity\Contact;
use app\models\entity\User;
use app\models\input\contacts\CreateContactForm;
use app\models\input\contacts\PatchContactForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class ContactsController extends BaseController
{
    use ContactResourceTrait;

    /**
     * @return \yii\web\Response
     */
    public function actionList()
    {
        $user = $this->getCurrentUser();

        $contacts = Contact::find()
            ->with('phones')
            ->where([
                'user_id' => $user->id()
            ])
            ->all();

        return $this->asJson(
            $this->contactCollectionResource($contacts)
        );
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionGet()
    {
        $contactId = $this->getRequestedContactId();
        $contact = $this->getContactForCurrentUser($contactId);

        return $this->asJson($this->contactResource($contact));
    }

    /**
     * @return \yii\web\Response
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        /**
         * @var $user User
         */
        $user =$this->getCurrentUser();
        $form = new CreateContactForm();

        $formLoaded =$form->load(Yii::$app->request->post(), '');
        $form->setCurrentUser($user);

        if ($formLoaded && $form->validate()) {

            $contact = $this->makeContact(
                $form->name,
                $form->surname,
                $form->patronymic,
                $user
            );

            return $this->asJson($this->contactResource($contact));
        }

        throw new UnprocessableEntityHttpException("Validation failed.");
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionPatch()
    {
        $contactId = $this->getRequestedContactId();
        $contact = $this->getContactForCurrentUser($contactId);

        $patchContactForm = new PatchContactForm();
        $formLoaded = $patchContactForm->load(Yii::$app->request->post(), '');
        $patchContactForm->setCurrentUser(
            $this->getCurrentUser()
        )->setCurrentContact(
            $contact
        );

        if ($formLoaded && $patchContactForm->validate()) {

            $contact->setName(
                $patchContactForm->name ?? $contact->name()
            )->setSurname(
                $patchContactForm->surname ?? $contact->surname()
            )->setPatronymic(
                $patchContactForm->patronymic ?? $contact->patronymic()
            );
            $contact->save();

            return $this->asJson($this->contactResource($contact));
        }

        throw new UnprocessableEntityHttpException("Validation failed.");
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $contactId = $this->getRequestedContactId();
        $contact = $this->getContactForCurrentUser($contactId);

        $contact->delete();
        return $this->asJson([]);
    }

    private function getRequestedContactId(): int
    {
        return (int)Yii::$app->request->get('id');
    }

    /**
     * @param int $contactId
     * @return Contact
     * @throws NotFoundHttpException
     */
    private function getContactForCurrentUser(int $contactId): Contact
    {
        $user = $this->getCurrentUser();

        /**
         * @var $contact Contact
         */
        $contact = Contact::find()
            ->where(['user_id' => $user->id()])
            ->where(['id' => $contactId])
            ->one();

        if (null === $contact) {
            throw new NotFoundHttpException("Contact not found!");
        }

        return $contact;
    }

    /**
     * @param string $name
     * @param string|null $surname
     * @param string|null $patronymic
     * @param User $user
     * @return Contact
     */
    private function makeContact(string $name, ?string $surname, ?string $patronymic, User $user): Contact
    {
        $contact = new Contact();

        $contact->setName($name)
            ->setSurname($surname)
            ->setPatronymic($patronymic)
            ->setUser($user);
        $contact->save();

        return $contact;
    }
}
