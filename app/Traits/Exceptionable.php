<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;

trait Exceptionable
{
    use ApiResponder;

    public function except(Exception $exception): array
    {
        if ($exception instanceof UniqueConstraintViolationException) {
            $status = 409;
            $message = __('public.already_exist');
        } else {
            $status = 400;
            $message = $exception->getMessage();
        }

        return [
            'status' => $status,
            'message' => $message
        ];
    }
}
