<?php

/**
 * This is the same as an auth token object, except this is for a collection set, which
 * will not contain the actual tokens themselves, because the token is not stored, but
 * only sent back in a response when the user creates a token.
 */

namespace Programster\CertManager\Models;

readonly class AuthTokenDetails
{
    public function __construct(
        public string         $id,
        public string         $name,
        public AuthTokenLevel $level,
        public string         $description,
    )
    {

    }
}