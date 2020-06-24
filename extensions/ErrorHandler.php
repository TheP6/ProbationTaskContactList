<?php

namespace extensions;

use extensions\exceptions\BaseHttpException;
use yii\web\ErrorHandler as YiiErrorHandler;

class ErrorHandler extends YiiErrorHandler
{
    protected function convertExceptionToArray($exception)
    {
        if ($exception instanceof BaseHttpException) {
            $converted = [
                'message' => $exception->getMessage(),
                'status'  => $exception->statusCode,
            ];

            if (!empty($exception->getBody())) {
                $converted = array_merge($converted, $exception->getBody());
            }

            return $converted;
        }

        return parent::convertExceptionToArray($exception);
    }
}