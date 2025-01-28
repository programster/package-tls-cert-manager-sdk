<?php

namespace Programster\CertManager\Models;

readonly class AuthToken
{
    public function __construct(
        public string         $id,
        public string         $token,
        public string         $name,
        public AuthTokenLevel $level,
        public string         $description,
    )
    {

    }
}