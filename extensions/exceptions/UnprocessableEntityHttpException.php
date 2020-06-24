<?php


namespace extensions\exceptions;

class UnprocessableEntityHttpException extends BaseHttpException
{
    /**
     * UnprocessableEntityHttpException constructor.
     * @param null $message
     * @param array $errors
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $errors = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct(422, $message, $errors, $code, $previous);
    }
}