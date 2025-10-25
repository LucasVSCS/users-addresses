<?php

namespace App\Exceptions\User;

use Exception;
use Illuminate\Support\Facades\Log;

class UserException extends Exception
{
    public function report()
    {
        Log::error('Erro de Usuário: ' . $this->getMessage());
        return false;
    }

    public function render($request)
    {
        $statusCode = $this->getCode() ?: 500;

        return response()->json([
            'message' => $this->getMessage(),
        ], $statusCode);
    }
}
