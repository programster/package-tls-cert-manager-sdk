<?php

namespace Programster\CertManager\Testing\Tests;

use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Testing\AbstractTest;
use Psr\Http\Client\ClientExceptionInterface;

class TestCreateAuthTokenAsAdmin extends AbstractTest
{
    public function getDescription(): string
    {
        return "Tests that we can successfully create an auth token as an admin.";
    }


    public function run()
    {
        $response = $this->getCertClient()->createAuthToken(
            $this->getFaker()->userName(),
            AuthTokenLevel::ADMIN,
            "A test token."
        );

        $this->m_passed = true;
    }
}
