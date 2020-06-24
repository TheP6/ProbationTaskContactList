<?php

namespace extensions\exceptions;

use yii\web\HttpException;

class BaseHttpException extends HttpException
{
    protected $body = [];

    public function __construct($status, $message = null, array $body = [], $code = 0, \Exception $previous = null)
    {
        $this->body = $body;
        parent::__construct($status, $message, $code, $previous);
    }

    public function setBody(array $body)
    {
        $this->body = $body;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}