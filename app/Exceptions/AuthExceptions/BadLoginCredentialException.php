<?php

namespace App\Exceptions\AuthExceptions;

use Exception;
use Throwable;

class BadLoginCredentialException extends Exception
{
    public function __construct($message = 'Please check the login credentials', $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
