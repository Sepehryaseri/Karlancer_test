<?php

namespace App\Exceptions\user;

use Exception;
use Throwable;

class UserActivationException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        $message = $message ?: __('user.activation.exception');
        parent::__construct($message, $code, $previous);
    }
}
