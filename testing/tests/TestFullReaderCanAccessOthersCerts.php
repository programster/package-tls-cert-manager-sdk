<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestFullReaderCanAccessOthersCerts extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that a \"full reader\" token can read others certs.";
    }


    public function run()
    {
        $creator1Token = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::CERTIFICATE_CREATOR,
            "A creator token"
        );

        $fullReaderToken = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::FULL_READ,
            "A reader token"
        );

        $creator1Client = $this->getCertClientForToken($creator1Token);
        $fullReaderClient = $this->getCertClientForToken($fullReaderToken);

        $createdCertificateId = $this->getFaker()->uuid();

        $creator1Client->createCertificateBundle(
            id: $createdCertificateId,
            name: "TestCreateCertificateAsAdmin",
            cert: "cert goes here",
            chain: "chain goes here",
            fullchain: "fullchain goes here",
            privateKey: "privateKey goes here",
        );

        try
        {
            $retrievedCert = $fullReaderClient->getCertificateBundle($createdCertificateId);
            $this->m_passed = true;
        }
        catch (ExceptionRequestFailed $ex)
        {
            $this->m_passed = false;
            $this->m_errorMessage = "Failed to retrieve certificate as a full reader.";
        }
    }
}
