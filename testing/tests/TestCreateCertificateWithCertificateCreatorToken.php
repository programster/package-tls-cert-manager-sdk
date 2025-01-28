<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestCreateCertificateWithCertificateCreatorToken extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that we can successfully create a certificate creator token, and then use that to create a certificate.";
    }


    public function run()
    {
        $creatorToken = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "A creator token"
        );

        $client2 = $this->getCertClientForToken($creatorToken);
        $createdCertificateId = $this->getFaker()->uuid();

        $certBundle = $client2->createCertificateBundle(
            id: $createdCertificateId,
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $this->m_passed = true;
    }
}
