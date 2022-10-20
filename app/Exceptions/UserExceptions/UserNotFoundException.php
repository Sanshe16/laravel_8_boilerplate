<?php

namespace App\Exceptions\UserExceptions;

use Exception;
use Throwable;

/**
 * Class GeneralException.
 */
class UserNotFoundException extends Exception
{
    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
