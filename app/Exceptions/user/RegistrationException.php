<?php

namespace App\Exceptions\user;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class RegistrationException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        $message = $message ?: __('user.register.exception');
        parent::__construct($message, $code, $previous);
    }
}
