<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Testing\AbstractTest;

class TestGetAllCertificatesAsAdmin extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that we can successfully create a certificate as an admin.";
    }


    public function run()
    {
        $client = $this->getCertClient();
        $certificateUuid = $this->getFaker()->uuid();

        $client->createCertificateBundle(
            id: $certificateUuid,
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $retrievedCertificates = $client->getCertificateBundles();
        $this->m_passed = true;
    }
}
