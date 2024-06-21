<?php

namespace App\Exceptions\user;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class CredentialException extends Exception
{
    public function __construct(string $message = "", int $code = 401, ?Throwable $previous = null)
    {
        $message = $message ?: __('user.credential_error');
        parent::__construct($message, $code, $previous);
    }
}
