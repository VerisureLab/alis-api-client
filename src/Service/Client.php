<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use VerisureLab\Library\AlisApiClient\Exception\ClientRequestException;

class Client
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $clientId, string $clientSecret, string $baseUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->client = new GuzzleClient([
            'base_uri' => $baseUri,
            'timeout' => 5,
            'http_errors' => true,
        ]);
    }

    /**
     * Retrieve token
     *
     * @param string $username
     * @param string $password
     *
     * @return array
     *
     * @throws ClientRequestException
     */
    public function obtainToken(string $username, string $password): array
    {
        return $this->handleRequest('POST', '/oauth/v2/token', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $username,
                'password' => $password,
            ]
        ]);
    }

    /**
     * Refresh token
     *
     * @param string $refreshToken
     *
     * @return array
     *
     * @throws ClientRequestException
     */
    public function refreshToken(string $refreshToken): array
    {
        return $this->handleRequest('POST', '/oauth/v2/token', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
            ]
        ]);
    }

    /**
     * Send lead data to alis endpoint
     *
     * @param string $token
     * @param array $requestData
     *
     * @return array
     *
     * @throws ClientRequestException
     */
    public function postLead(string $token, array $requestData): array
    {
        return $this->handleRequest('POST', '/leads', [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
            \GuzzleHttp\RequestOptions::JSON => $requestData
        ]);
    }

    /**
     * Execute the request
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     *
     * @return array
     *
     * @throws ClientRequestException
     */
    private function handleRequest(string $method, string $uri, array $params): array
    {
        try {
            $response = $this->client->request($method, $uri, $params);
        } catch (RequestException $e) {
            throw new ClientRequestException(Psr7\str($e->getRequest()), $e->getCode(), $e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}