<?php

namespace Programster\CertManager\Models;

readonly class CertificateBundle
{
    public function __construct(
        public string $name,
        public string $cert,
        public string $chain,
        public string $fullchain,
        public string $privateKey,
    )
    {

    }
}