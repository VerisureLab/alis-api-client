<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use GuzzleHttp\Client;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;
use VerisureLab\Library\AAAApiClient\Service\AuthenticationService;
use VerisureLab\Library\AlisApiClient\Exception\AuthenticationRequiredException;
use VerisureLab\Library\AlisApiClient\Exception\TokenNotFoundException;
use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;

abstract class AbstractApiClient implements ApiClientInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var Client
     */
    private $client;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationService $authenticationService, string $baseUri)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationService = $authenticationService;

        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => 5,
            'http_errors' => true,
        ]);
    }

    /**
     * Api call create
     *
     * @param array $data
     * @param Credentials|null $credentials
     *
     * @return string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callCreate(array $data, ?Credentials $credentials = null): string
    {
        $params = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
            ],
            \GuzzleHttp\RequestOptions::JSON => $data
        ];

        return $this->handleRequest('POST', sprintf('/%s', $this->getResource()), $params)['id'];
    }

    /**
     * Api call create
     *
     * @param string $id
     * @param array $data
     * @param Credentials|null $credentials
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callUpdate(string $id, array $data, ?Credentials $credentials = null): array
    {
        $params = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
            ],
            \GuzzleHttp\RequestOptions::JSON => $data
        ];

        return $this->handleRequest('PUT', sprintf('/%s/%s', $this->getResource(), $id), $params);
    }

    /**
     * Api call detail
     *
     * @param string $id
     * @param Credentials|null $credentials
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callDetail(string $id, ?Credentials $credentials = null): array
    {
        $params = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
            ]
        ];

        return $this->handleRequest('GET', sprintf('/%s/%s', $this->getResource(), $id), $params);
    }

    /**
     * Api call list
     *
     * @param int $page
     * @param array $query
     * @param Credentials|null $credentials
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callList(int $page = 1, array $query = [], ?Credentials $credentials = null): array
    {
        $params = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
            ],
            \GuzzleHttp\RequestOptions::QUERY => array_merge($query, [
                'page' => $page
            ]),
        ];

        return $this->handleRequest('GET', sprintf('/%s', $this->getResource()), $params);
    }

    /**
     * Api call delete
     *
     * @param string $id
     * @param Credentials|null $credentials
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callDelete(string $id, ?Credentials $credentials = null): void
    {
        $params = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
            ]
        ];

        $this->handleRequest('DELETE', sprintf('/%s/%s', $this->getResource(), $id), $params);
    }

    /**
     * Get bearer token
     *
     * @param null|Credentials $credentials
     *
     * @return string
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getBearerToken(?Credentials $credentials = null): string
    {
        try {
            $token = $this->tokenStorage->read();
        } catch (TokenNotFoundException $e) {
            if (null !== $credentials) {
                $token = $this->authenticationService->authenticate($credentials->getUsername(), $credentials->getPassword());
                $this->tokenStorage->save($token);
            } else {
                throw $e;
            }
        }

        if ($token->getExpiresAt() < new \DateTime()) {
            try {
                $token = $this->authenticationService->refreshToken($token->getRefreshToken());
            } catch (ClientRequestException $e) {
                if (null === $credentials) {
                    throw $e;
                }
                $token = $this->authenticationService->authenticate($credentials->getUsername(), $credentials->getPassword());
            }
            $this->tokenStorage->save($token);
        }

        return $token->getAccessToken();
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function handleRequest(string $method, string $uri, array $params): array
    {
        try {
            $response = $this->client->request($method, $uri, $params);
        } catch (TokenNotFoundException $e) {
            throw new AuthenticationRequiredException('No token found', 500, $e);
        } catch (ClientRequestException $e) {
            throw new AuthenticationRequiredException('Cannot refresh token', 500, $e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get resource name
     *
     * @return string
     */
    abstract protected function getResource(): string;
}