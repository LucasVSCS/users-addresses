<?php

namespace App\Exceptions\User;

class UserStoreException extends UserException
{
    /**
     * Construtor da exceção.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = 'Erro ao criar o usuário.', $code = 500, \Throwable $previous = null)
    {
        // Passa a exceção anterior ($e) para manter o stack trace original
        parent::__construct($message, $code, $previous);
    }
}
