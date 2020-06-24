<?php

namespace app\controllers;

use Yii;
use app\models\entity\User;
use yii\rest\Controller as RestController;

class BaseController extends RestController
{
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