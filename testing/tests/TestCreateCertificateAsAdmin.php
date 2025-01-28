<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestCreateCertificateAsAdmin extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that we can successfully retrieve a certificate as an admin.";
    }


    public function run()
    {
        $response = $this->getCertClient()->createCertificateBundle(
            id: $this->getFaker()->uuid(),
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $this->m_passed = true;
    }
}
