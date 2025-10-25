<?php

namespace App\Exceptions\User;

class UserUpdateException extends UserException
{
    /**
     * Construtor da exceção.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = 'Erro ao atualizar o usuário.', $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
