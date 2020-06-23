<?php

namespace app\controllers;

use yii\rest\Controller as RestController;

class DefaultController extends RestController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
