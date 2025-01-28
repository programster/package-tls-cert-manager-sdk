<?php


namespace Programster\CertManager\Exceptions;

use Psr\Http\Message\ResponseInterface;

class ExceptionRequestFailed extends \Exception
{
    private ResponseInterface $m_response;


    public function __construct(ResponseInterface $response)
    {
        $this->m_response = $response;
        parent::__construct("Request failed");
    }


    public function getResponse(): ResponseInterface
    {
        return $this->m_response;
    }
}
