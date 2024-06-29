<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class DueDateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (date('Y-m-d H:i:s', strtotime($value)) < date('Y-m-d H:i:s')) {
            $fail('taskTitle.due_date_passed')->translate();
        }
    }
}
