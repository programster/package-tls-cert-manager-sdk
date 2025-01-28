<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;

class TestCannotGetAllCertificatesAsCreator extends AbstractTest
{
    public function getDescription(): string
    {
        return "Test that a full reader can get all certificats.";
    }


    public function run()
    {
        $this->m_passed = false;
        $client = $this->getCertClient();

        $creatorToken = $client->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "a full read token"
        );

        $creatorClient = $this->getCertClientForToken($creatorToken);

        $certificateUuid = $this->getFaker()->uuid();

        $client->createCertificateBundle(
            id: $certificateUuid,
            name: "TestGetAllCertificatesAsFullReader",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $retrievedCertificates = $creatorClient->getCertificateBundles();

        if (count($retrievedCertificates) > 0)
        {
            $this->m_passed = false;
            $this->m_errorMessage = "Retrieved certificates that should not have access to.";
        }
        else
        {
            $this->m_passed = true;
        }
    }
}
