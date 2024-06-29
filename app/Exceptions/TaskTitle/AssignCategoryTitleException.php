<?php

namespace App\Exceptions\TaskTitle;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class AssignCategoryTitleException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        $message = $message ?: __('taskTitle.assign_category_exception');
        parent::__construct($message, $code, $previous);
    }
}
