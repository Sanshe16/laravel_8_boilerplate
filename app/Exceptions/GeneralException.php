<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class GeneralException.
 */
class GeneralException extends Exception
{
    Protected $payload;

    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 409, $payload = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->payload = $payload;
    }

    /**
     * Get the payload from the exception.
     *
     * @param  string  $getPayload
     * @return $this
     */
    public function getPayload()
    {
        return $this->payload;
    }

}
