<?php

namespace app\controllers;

use app\models\entity\Contact;
use app\models\entity\Phone;
use app\models\entity\User;
use app\models\input\contacts\CreatePhoneForm;
use app\models\input\contacts\PatchPhoneForm;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller as RestController;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class PhonesController extends RestController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'list',
                            'get',
                            'create',
                            'delete'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'list'   => ['get'],
                    'create' => ['post'],
                    'get'    => ['get'],
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

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
                ->setPhone($form->phone)
                ->setLabel($form->label)
                ->setContact($form->getContact());


            return $this->asJson($this->phoneResource($phone));
        }

        throw new UnprocessableEntityHttpException("Validation failed.");
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
                ->setPhone($form->phone ?? $phone->phone())
                ->setLabel($form->label ?? $phone->label())
                ->setContact($form->getContact() ?? $phone->contact());
            $phone->save();

            return $this->asJson($this->phoneResource($phone));
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
            ->where(["{$contactTable}.'.user_id'" => $user->id()])
            ->andWhere(["{$phoneTable}.id" => $phoneId])
            ->one();

        if (null === $phone) {
            throw new NotFoundHttpException("Phone not found!");
        }

        return $phone;
    }

    /**
     * @return User
     */
    private function getCurrentUser(): User
    {
        /**
         * @var $user User
         */
        $user =  Yii::$app->user->identity;;

        return $user;
    }

    /**
     * @param Phone $phone
     * @return array
     */
    private function phoneResource(Phone $phone): array
    {
        return [
            'id'          => $phone->id(),
            'number'      => $phone->phone(),
            'label'       => $phone->label(),
        ];
    }
}
