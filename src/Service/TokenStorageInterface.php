<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\Exception\CannotSaveTokenException;
use VerisureLab\Library\AlisApiClient\Exception\TokenNotFoundException;
use VerisureLab\Library\AlisApiClient\ValueObject\Token;

interface TokenStorageInterface
{
    /**
     * Save token
     *
     * @param Token $token
     *
     * @throws CannotSaveTokenException
     */
    public function save(Token $token): void;

    /**
     * Read token
     *
     * @return Token
     *
     * @throws TokenNotFoundException
     */
    public function read(): Token;

    /**
     * Unset token
     */
    public function unset(): void;
}