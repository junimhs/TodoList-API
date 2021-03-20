<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ForgotPasswordExistsException extends Exception
{
    protected $message = 'Password recovery already requested.';

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => class_basename($this),
            'message' => $this->getMessage()
        ], 400);
    }
}
