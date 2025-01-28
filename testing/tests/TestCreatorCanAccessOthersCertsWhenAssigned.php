<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestCreatorCanAccessOthersCertsWhenAssigned extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that a creator cannot access other creators certificates if they haven't been specifially assigned to them..";
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
        $creator2Client = $this->getCertClientForToken($creator2Token);

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
        $retrievedCert = $creator2Client->getCertificateBundle($createdCertificateId);
        $this->m_passed = true;
    }
}
