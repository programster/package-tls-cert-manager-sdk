<?php

namespace Programster\CertManager\Models;

readonly class CertificateBundleSetItem
{
    public function __construct(
        public string                     $id,
        public string                     $name,
        public AuthTokenDetailsCollection $authTokens,
    )
    {

    }
}