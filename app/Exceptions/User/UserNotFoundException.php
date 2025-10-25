<?php

namespace App\Exceptions\User;

class UserNotFoundException extends UserException
{
    /**
     * Construtor da exceção.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = 'Usuário não encontrado.', $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
