<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;

trait Exceptionable
{
    use ApiResponder;

    public function except(Exception $exception): array
    {
        return [
            'status' => 400,
            'message' => $exception->getMessage()
        ];
    }
}
