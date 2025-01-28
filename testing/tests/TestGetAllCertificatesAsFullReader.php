<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;

class TestGetAllCertificatesAsFullReader extends AbstractTest
{
    public function getDescription(): string
    {
        return "Test that a full reader can get all certificats.";
    }


    public function run()
    {
        $this->m_passed = false;
        $client = $this->getCertClient();
        $fullReaderToken = $client->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::FULL_READ,
            "a full read token"
        );

        $fullReaderClient = $this->getCertClientForToken($fullReaderToken);

        $certificateUuid = $this->getFaker()->uuid();

        $client->createCertificateBundle(
            id: $certificateUuid,
            name: "TestGetAllCertificatesAsFullReader",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $retrievedCertificates = $fullReaderClient->getCertificateBundles();

        if (count($retrievedCertificates) > 0)
        {
            $this->m_passed = true;
        }
        else
        {
            $this->m_passed = false;
            $this->m_errorMessage = "Failed to retrieve any certificates.";
        }
    }
}
