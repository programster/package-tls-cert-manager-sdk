<?php

namespace Programster\CertManager\Models;

class CertificateBundle
{
    public function __construct(
        public readonly string $name,
        public readonly string $cert,
        public readonly string $chain,
        public readonly string $fullchain,
        public readonly string $privateKey,
    )
    {

    }
}