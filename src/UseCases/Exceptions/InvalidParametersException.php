<?php

namespace Atenas\UseCases\Exceptions;

class InvalidParametersException extends \Exception
{
    protected $errors = array();

    /**
     * InvalidParametersException constructor.
     * @param $errors array
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct();
    }

    public function getErrors():array
    {
        return $this->errors;
    }
}