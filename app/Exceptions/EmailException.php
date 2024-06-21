<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EmailException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        $message = $message ?: __('mail.exception');
        parent::__construct($message, $code, $previous);
    }
}
