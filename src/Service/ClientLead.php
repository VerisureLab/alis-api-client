<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;
use VerisureLab\Library\AlisApiClient\ValueObject\Lead;

class ClientLead extends AbstractApiClient
{
    public function create(string $sourceId, Lead $lead, ?Credentials $credentials = null): string
    {
        return $this->callCreate([
            'source' => sprintf('/sources/%s', $sourceId),
            'phoneNumber' => $lead->getPhoneNumber(),
            'zipCode' => $lead->getZipCode(),
            'data' => $lead->getData(),
        ], $credentials);
    }

    public function detail(string $id, ?Credentials $credentials = null): array
    {
        return $this->callDetail($id, $credentials);
    }

    public function list(int $page = 1, array $query = [], ?Credentials $credentials = null): array
    {
        return $this->callList($page, $query, $credentials);
    }

    protected function getResource(): string
    {
        return 'leads';
    }
}