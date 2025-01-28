<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestAssignAuthTokenAsAdmin extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that an admin can assign a certificate to another user.";
    }


    public function run()
    {
        $creator1Token = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "A creator token"
        );

        $creator2Token = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "A second creator token"
        );

        $creator1Client = $this->getCertClientForToken($creator1Token);
        $createdCertificateId = $this->getFaker()->uuid();

        $creator1Client->createCertificateBundle(
            id: $createdCertificateId,
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        $this->getCertClient()->assignAuthToken($creator2Token->id, $createdCertificateId);
        $this->m_passed = true;
    }
}
