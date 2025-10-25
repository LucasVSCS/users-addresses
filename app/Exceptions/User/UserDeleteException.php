<?php

namespace App\Exceptions\User;

class UserDeleteException extends UserException
{
    /**
     * Construtor da exceção.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = 'Erro ao deletar o usuário.', $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
