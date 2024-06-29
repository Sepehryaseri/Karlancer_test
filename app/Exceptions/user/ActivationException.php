<?php

namespace App\Exceptions\user;

use Exception;
use Throwable;

class ActivationException extends Exception
{
    public function __construct(string $message = "", int $code = 403, ?Throwable $previous = null)
    {
        $message = $message ?: __('user.inactive');
        parent::__construct($message, $code, $previous);
    }
}
