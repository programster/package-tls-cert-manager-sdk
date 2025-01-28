<?php

namespace Programster\CertManager\Models;

use Programster\Collections\AbstractCollection;

class CertificateBundleSet extends AbstractCollection
{
    public function __construct(
        CertificateBundleSetItem ...$certificates
    )
    {
        parent::__construct(
            CertificateBundleSetItem::class, ...$certificates
        );
    }
}