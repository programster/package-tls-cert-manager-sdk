<?php

/*
 * Abstract class all tests should extend.
 */

namespace Programster\CertManager\Testing;

use Faker\Factory;
use Programster\CertManager\CertManagerClient;
use Programster\CertManager\Exceptions\ExceptionRequestFailed;
use Programster\CertManager\Models\AuthToken;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\UuidFactory;


abstract class AbstractTest
{
    protected bool $m_passed = false;
    protected string $m_errorMessage;
    abstract public function getDescription() : string;
    abstract public function run();
    public function getPassed(): bool { return $this->m_passed; }


    public function runTest()
    {
        try
        {
            $this->run();
        }
        catch (ExceptionRequestFailed $e1)
        {
            try
            {
                $responseContent = $e1->getResponse()->getBody()->getContents();
                $responseArray = json_decode(json: $responseContent, associative: true, flags: JSON_THROW_ON_ERROR);
                $this->m_errorMessage = $responseArray['error']['message'];
            }
            catch(\JsonException)
            {
                $this->m_errorMessage = "API gave a non JSON response - $responseContent";
            }
        }
        catch (\Exception $ex)
        {
            $this->m_passed = false;
        }
    }


    protected function getCertClient() : CertManagerClient
    {
        $messagingClient = new \GuzzleHttp\Client();
        $requestFactory = new \GuzzleHttp\Psr7\HttpFactory();

        return new CertManagerClient(
            $messagingClient,
            $requestFactory,
            ADMIN_AUTH_ID,
            ADMIN_AUTH_SECRET,
            "http://127.0.0.1"
        );
    }


    protected function getCertClientForToken(AuthToken $token) : CertManagerClient
    {
        $messagingClient = new \GuzzleHttp\Client();
        $requestFactory = new \GuzzleHttp\Psr7\HttpFactory();

        return new CertManagerClient(
            $messagingClient,
            $requestFactory,
            $token->id,
            $token->token,
            "http://127.0.0.1"
        );
    }


    protected function getFaker()
    {
        static $faker = null;

        if ($faker === null)
        {
            $faker = Factory::create();
        }

        return $faker;
    }


    protected function generateUuid()
    {
        static $factory = null;

        if ($factory === null)
        {
            $factory = new UuidFactory();

            $generator = new CombGenerator(
                $factory->getRandomGenerator(),
                $factory->getNumberConverter()
            );

            $codec = new Ramsey\Uuid\Codec\TimestampFirstCombCodec($factory->getUuidBuilder());

            $factory->setRandomGenerator($generator);
            $factory->setCodec($codec);

            Ramsey\Uuid\Uuid::setFactory($factory);
        }

        return Ramsey\Uuid\Uuid::uuid4()->toString();
    }


    public function getErrorMessage() : string { return $this->m_errorMessage ?? ""; }
}