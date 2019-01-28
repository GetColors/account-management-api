<?php

namespace Atenas\Models\User;

class DatabaseException extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}