<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestNormalTokenCanAccessOthersCertsWhenAssigned extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that a normal token can access a certificate when assigned to it.";
    }


    public function run()
    {
        $creator1Token = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "A creator token"
        );

        $normalToken = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::NORMAL,
            "A normal token"
        );

        $creatorClient = $this->getCertClientForToken($creator1Token);
        $normalClient = $this->getCertClientForToken($normalToken);

        $createdCertificateId = $this->getFaker()->uuid();

        $creatorClient->createCertificateBundle(
            id: $createdCertificateId,
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $this->getCertClient()->assignAuthToken($normalToken->id, $createdCertificateId);
        $retrievedCert = $normalClient->getCertificateBundle($createdCertificateId);
        $this->m_passed = true;
    }
}
