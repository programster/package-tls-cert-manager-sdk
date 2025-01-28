<?php

/**
 * This is the same as an auth token object, except this is for a collection set, which
 * will not contain the actual tokens themselves, because the token is not stored, but
 * only sent back in a response when the user creates a token.
 */

namespace Programster\CertManager\Models;

class AuthTokenDetails
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly AuthTokenLevel $level,
        public readonly string $description,
    )
    {

    }
}