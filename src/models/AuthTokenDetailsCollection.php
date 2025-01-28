<?php

namespace Programster\CertManager\Models;

use Programster\Collections\AbstractCollection;

class AuthTokenDetailsCollection extends AbstractCollection
{
    public function __construct(
        AuthTokenDetails ...$authTokens
    )
    {
        parent::__construct(
            AuthTokenDetails::class,
            ...$authTokens,
        );
    }
}