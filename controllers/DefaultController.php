<?php

namespace app\controllers;

use yii\rest\Controller as RestController;
use yii\web\ErrorAction;

class DefaultController extends RestController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}
