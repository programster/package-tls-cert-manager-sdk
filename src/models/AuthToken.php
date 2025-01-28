<?php

namespace Programster\CertManager\Models;

class AuthToken
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly string $name,
        public readonly AuthTokenLevel $level,
        public readonly string $description,
    )
    {

    }
}