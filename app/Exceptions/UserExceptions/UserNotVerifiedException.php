<?php

namespace App\Exceptions\UserExceptions;

use Exception;
use Throwable;

/**
 * Class GeneralException.
 */
class UserNotVerifiedException extends Exception
{
    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
