<?php

namespace app\controllers;

use app\controllers\resources\PhoneResourceTrait;
use app\models\entity\Contact;
use app\models\entity\Phone;
use app\models\input\phones\CreatePhoneForm;
use app\models\input\phones\PatchPhoneForm;
use Yii;
use yii\web\NotFoundHttpException;
use extensions\exceptions\UnprocessableEntityHttpException;

class PhonesController extends BaseController
{
    use PhoneResourceTrait;

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionGet()
    {
        $phoneId = $this->getRequestedPhoneId();
        $phone = $this->getPhoneForCurrentUser($phoneId);

        return $this->asJson($this->phoneResource($phone));
    }

    /**
     * @return \yii\web\Response
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        $user = $this->getCurrentUser();
        $form = new CreatePhoneForm();

        $formLoaded =$form->load(Yii::$app->request->post(), '');
        $form->setCurrentUser($user);

        if ($formLoaded && $form->validate()) {

            $phone = new Phone();
            $phone
                ->setNumber($form->phone)
                ->setLabel($form->label)
                ->setContact($form->getContact());
            $phone->save();

            return $this->asJson($this->phoneResource($phone));
        }

        throw new UnprocessableEntityHttpException("Validation failed.", ['errors' => $form->getErrors()]);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionPatch()
    {
        $phoneId = $this->getRequestedPhoneId();
        $phone = $this->getPhoneForCurrentUser($phoneId);

        $form = new PatchPhoneForm();
        $formLoaded = $form->load(Yii::$app->request->post(), '');
        $form->setCurrentUser(
            $this->getCurrentUser()
        );

        if ($formLoaded && $form->validate()) {

            $phone
                ->setNumber($form->phone ?? $phone->number())
                ->setLabel($form->label ?? $phone->label())
                ->setContact($form->getContact() ?? $phone->contact());
            $phone->save();

            return $this->asJson($this->phoneResource($phone));
        }

        throw new UnprocessableEntityHttpException("Validation failed.", ['errors' => $form->getErrors()]);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $phoneId = $this->getRequestedPhoneId();
        $phone = $this->getPhoneForCurrentUser($phoneId);

        $phone->delete();
        return $this->asJson([]);
    }

    /**
     * @return int
     */
    private function getRequestedPhoneId(): int
    {
        return (int)Yii::$app->request->get('id');
    }

    /**
     * @param int $phoneId
     * @return Phone
     * @throws NotFoundHttpException
     */
    private function getPhoneForCurrentUser(int $phoneId): Phone
    {
        $user = $this->getCurrentUser();

        $phoneTable = Phone::tableName();
        $contactTable = Contact::tableName();

        /**
         * @var $phone Phone
         */
        $phone = Phone::find()
            ->select("{$phoneTable}.*")
            ->innerJoin(
                $contactTable,
                "`{$phoneTable}`.`contact_id` = `{$contactTable}`.`id`"
            )
            ->where(["{$contactTable}.`user_id`" => $user->id()])
            ->andWhere(["`{$phoneTable}`.`id`" => $phoneId])
            ->one();

        if (null === $phone) {
            throw new NotFoundHttpException("Phone not found!");
        }

        return $phone;
    }
}
