<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use GuzzleHttp\Client;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;
use VerisureLab\Library\AAAApiClient\Service\AuthenticationService;
use VerisureLab\Library\AlisApiClient\Exception\AuthenticationRequiredException;
use VerisureLab\Library\AlisApiClient\Exception\TokenNotFoundException;
use VerisureLab\Library\AlisApiClient\Exception\TransmitLeadException;
use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;
use VerisureLab\Library\AlisApiClient\ValueObject\Lead;

class Transmitter
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
     * Send lead to alis service
     *
     * @param string $sourceId
     * @param Lead $lead
     * @param null|Credentials $credentials
     *
     * @return string
     *
     * @throws TransmitLeadException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $sourceId, Lead $lead, ?Credentials $credentials = null): string
    {
        try {
            $response = $this->client->post('/leads', [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer '.$this->getBearerToken($credentials),
                    'X-Access-Type' => 'customer',
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'source' => sprintf('/sources/%s', $sourceId),
                    'phoneNumber' => $lead->getPhoneNumber(),
                    'zipCode' => $lead->getZipCode(),
                    'data' => $lead->getData(),
                ]
            ]);
        } catch (TokenNotFoundException $e) {
            throw new AuthenticationRequiredException('No token found', 500, $e);
        } catch (ClientRequestException $e) {
            throw new AuthenticationRequiredException('Cannot refresh token', 500, $e);
        } catch (\Exception $e) {
            throw new TransmitLeadException($sourceId, $lead, $e);
        }

        return json_decode($response->getBody()->getContents(), true)['id'];
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
    private function getBearerToken(?Credentials $credentials = null): string
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
}