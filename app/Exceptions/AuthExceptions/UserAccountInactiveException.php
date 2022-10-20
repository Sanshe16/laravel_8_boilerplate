<?php

namespace App\Exceptions\AuthExceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class GeneralException.
 */
class UserAccountInactiveException extends Exception
{
    Protected $payload;
    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = Response::HTTP_FORBIDDEN, $payload = [], Throwable $previous = null)
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
