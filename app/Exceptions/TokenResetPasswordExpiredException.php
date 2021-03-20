<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TokenResetPasswordExpiredException extends Exception
{
    protected $message = 'Token expired.';

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
