<?php

namespace Programster\CertManager\Models;

class CertificateBundleSetItem
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly AuthTokenDetailsCollection $authTokens,
    )
    {

    }
}