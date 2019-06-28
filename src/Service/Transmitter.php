<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\Exception\AuthenticationRequiredException;
use VerisureLab\Library\AlisApiClient\Exception\ClientRequestException;
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

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationService $authenticationService, Client $client)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationService = $authenticationService;
        $this->client = $client;
    }

    /**
     * Send lead to alis service
     *
     * @param string $sourceId
     * @param Lead $lead
     * @param null|Credentials $credentials
     *
     * @return string
     * @throws TransmitLeadException
     */
    public function send(string $sourceId, Lead $lead, ?Credentials $credentials = null): string
    {
        try {
            $responseData = $this->client->postLead(
                $this->getBearerToken($credentials),
                [
                    'source' => sprintf('/sources/%s', $sourceId),
                    'phoneNumber' => $lead->getPhoneNumber(),
                    'zipCode' => $lead->getZipCode(),
                    'data' => $lead->getData(),
                ]
            );
        } catch (TokenNotFoundException $e) {
            throw new AuthenticationRequiredException('No token found', 500, $e);
        } catch (\Exception $e) {
            throw new TransmitLeadException($sourceId, $lead, $e);
        }

        return $responseData['id'];
    }

    /**
     * Get bearer token
     *
     * @param null|Credentials $credentials
     *
     * @return string
     *
     * @throws \Exception
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