<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestCannotCreateCertificateWithNormalToken extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that we can successfully create a certificate creator token, and then use that to create a certificate.";
    }


    public function run()
    {
        $creatorToken = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::NORMAL,
            "A creator token"
        );

        $client2 = $this->getCertClientForToken($creatorToken);
        $createdCertificateId = $this->getFaker()->uuid();

        try
        {
            $certBundle = $client2->createCertificateBundle(
                id: $createdCertificateId,
                name: "TestCreateCertificateAsAdmin",
                cert: "cert goes here",
                chain: "chain goes here",
                fullchain: "fullchain goes here",
                privateKey: "privateKey goes here",
            );

            // should not get hear, prevous request should fail.
            $this->m_passed = false;
        }
        catch (ExceptionRequestFailed $failedResponse)
        {
            if ($failedResponse->getResponse()->getStatusCode() == 403)
            {
                $this->m_passed = true;
            }
        }
    }
}
