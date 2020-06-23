<?php

namespace app\controllers;

use app\models\entity\User;
use app\models\input\RegisterForm;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller as RestController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\input\LoginForm;
use yii\web\UnprocessableEntityHttpException;

class UserSessionController extends RestController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout'    => ['post'],
                    'login'     => ['post'],
                    'register'  => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            throw new NotFoundHttpException();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $model->password = '';
            $user = $model->getUser();

            return $this->asJson([
                'username' => $user->login(),
            ]);
        }

        throw new UnprocessableEntityHttpException("Login or password is not correct");
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->asJson([]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            throw new NotFoundHttpException();
        }

        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            $user = new User();
            $user->setLogin(
                $model->name
            )->setPasswordHash(
                password_hash($model->password, PASSWORD_BCRYPT)
            )->save();

            return $this->asJson([
                'id' => $user->id(),
                'login' => $user->login(),
            ]);
        }

        throw new UnprocessableEntityHttpException("Validation error.");
    }
}
