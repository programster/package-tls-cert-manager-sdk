<?php

/*
 * A library of functions that are only useful for CLI-based applications/scripts.
 */

namespace Programster\CertManager;


use Programster\CertManager\Models\AuthToken;
use Programster\CertManager\Models\AuthTokenDetails;
use Programster\CertManager\Models\AuthTokenDetailsCollection;
use Programster\CertManager\Models\AuthTokenLevel;
use Programster\CertManager\Models\CertificateBundle;
use Programster\CertManager\Models\CertificateBundleSet;
use Programster\CertManager\Models\CertificateBundleSetItem;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class CertManagerClient
{
    private string $m_apiBaseUri;


    public function __construct(
        private ClientInterface $messagingClient,
        private RequestFactoryInterface $requestFactory,
        private readonly string $authTokenId,
        private readonly string $authTokenSecret,
        string $url,
    )
    {
        $this->m_apiBaseUri = $url;
    }

    public function createAuthToken(
        string $name,
        AuthTokenLevel $level,
        string $description
    ) : AuthToken
    {
        $endpoint = "/api/auth-tokens";
        $method = "POST";

        $request = $this->createRequest($method, $endpoint);

        $request->getBody()->write(json_encode([
            "name" => $name,
            "level" => $level->value,
            "description" => $description,
        ]));

        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 201)
        {
            $responseBody = $response->getBody()->getContents();
            $responseArray = json_decode($responseBody, true);

            $authToken = new AuthToken(
                $responseArray['id'],
                $responseArray['token'],
                $responseArray['name'],
                AuthTokenLevel::from($responseArray['level']),
                $responseArray['description'],
            );
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }

        return $authToken;
    }


    public function deleteAuthToken(string $authTokenId) : void
    {
        $endpoint = "/api/auth-tokens/{$authTokenId}";
        $method = "DELETE";

        $request = $this->createRequest($method, $endpoint);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 200)
        {
            // success do nothing
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }


    public function getAuthTokenAssignments()
    {
        $endpoint = "/api/auth-token-assignments";
        $method = "GET";
    }

    public function assignAuthToken(string $authTokenId, string $certificateBundleId)
    {
        $endpoint = "/api/auth-token-assignments";
        $method = "POST";

        $body = [
            'auth_token_id' => $authTokenId,
            'certificate_bundle_id' => $certificateBundleId,
        ];

        $request = $this->createRequest($method, $endpoint);
        $request->getBody()->write(json_encode($body));
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 201)
        {
            // success do nothing
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }

    public function deleteAuthTokenAssignment(string $assignmentId)
    {
        $endpoint = "/api/auth-token-assignments";
        $method = "DELETE";

        $request = $this->createRequest($method, $endpoint);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 200)
        {
            // success do nothing
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }


    public function getCertificateBundles() : CertificateBundleSet
    {
        $endpoint = "/api/certs";
        $method = "GET";

        $request = $this->createRequest($method, $endpoint);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 200)
        {
            $responseArray = json_decode($response->getBody()->getContents(), true);
            $certificateBundleSet = new CertificateBundleSet();

            foreach ($responseArray as $detailsForCertificateBundleSetItem)
            {
                $tokenDetailsObjects = new AuthTokenDetailsCollection();
                $tokenDetails = $detailsForCertificateBundleSetItem['auth_tokens'];

                foreach ($tokenDetails as $tokenDetailArray)
                {
                    $tokenDetailsObjects[] = new AuthTokenDetails(
                        $tokenDetailArray['id'],
                        $tokenDetailArray['name'],
                        AuthTokenLevel::from($tokenDetailArray['level']),
                        $tokenDetailArray['description'],
                    );

                }

                $certificateBundleSet[] = new CertificateBundleSetItem(
                    $detailsForCertificateBundleSetItem['id'],
                    $detailsForCertificateBundleSetItem['name'],
                    $tokenDetailsObjects
                );
            }

            return $certificateBundleSet;
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }


    public function getCertificateBundle(string $id)
    {
        $endpoint = "/api/certs/{$id}";
        $method = "GET";

        $request = $this->createRequest($method, $endpoint);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 200)
        {
            $responseArray = json_decode($response->getBody()->getContents(), true);

            return new CertificateBundle(
                $responseArray['name'],
                $responseArray['cert'],
                $responseArray['chain'],
                $responseArray['fullchain'],
                $responseArray['private_key'],
            );
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }


    /**
     *
     * @param string $id - a UUID to give the new certificate.
     * @param string $name - the name to give the new certificate
     * @param string $cert - the site certificate.
     * @param string $chain - the chain file.
     * @param string $fullchain - the chain combined with the site certificate
     * @param string $privateKey - the private key that relates to the certificate.
     * @return ResponseInterface - if success.
     * @throws Exceptions\ExceptionRequestFailed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createCertificateBundle(
        string $id,
        string $name,
        string $cert,
        string $chain,
        string $fullchain,
        string $privateKey,
    ) : ResponseInterface
    {
        $endpoint = "/api/certs";
        $method = "POST";

        $body = [
            'id' => $id,
            'name' => $name,
            'cert' => $cert,
            'chain' => $chain,
            'fullchain' => $fullchain,
            'private_key' => $privateKey,
        ];

        $request = $this->createRequest($method, $endpoint, $body);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 201)
        {
            // success, do nothing.
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }

        return $response;
    }


    public function updateCertificateBundle(
        string $setId,
        ?string $name,
        ?string $cert,
        ?string $chain,
        ?string $fullchain,
        ?string $privateKey,
    ) : void
    {
        $endpoint = "/api/certs/{$setId}";
        $method = "PATCH";
        $body = [];

        if ($name !== null)
        {
            $body['name'] = $name;
        }

        if ($cert !== null)
        {
            $body['cert'] = $cert;
        }

        if ($chain !== null)
        {
            $body['chain'] = $chain;
        }

        if ($fullchain !== null)
        {
            $body['fullchain'] = $fullchain;
        }

        if ($privateKey !== null)
        {
            $body['private_key'] = $privateKey;
        }

        $request = $this->createRequest($method, $endpoint, $body);
        $response = $this->messagingClient->sendRequest($request);

        if ($response->getStatusCode() === 200)
        {
            // success, do nothing.
        }
        else
        {
            throw new Exceptions\ExceptionRequestFailed($response);
        }
    }


    private function createRequest(string $method, string $endpoint, ?array $body = null, array $headers = [])
    {
        $httpBasicAuthValue = "Basic " . base64_encode("{$this->authTokenId}:{$this->authTokenSecret}");

        $request = $this->requestFactory->createRequest(
            $method,
            $this->m_apiBaseUri . $endpoint
        );

        $request = $request->withHeader("content-Type", "application/json");
        $request = $request->withHeader("Accept", "application/json");
        $request = $request->withHeader("Authorization", $httpBasicAuthValue);

        if ($body !== null)
        {
            $request->getBody()->write(json_encode($body));
        }

        return $request;
    }
}