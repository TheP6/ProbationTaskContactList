<?php

namespace app\controllers;

use Yii;
use app\models\entity\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rest\Controller as RestController;

class BaseController extends RestController
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
                            'patch',
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
                    'patch'  => ['patch'],
                    'get'    => ['get'],
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    /**
     * @return User
     */
    protected function getCurrentUser(): User
    {
        /**
         * @var $user User
         */
        $user =  Yii::$app->user->identity;;

        return $user;
    }
}