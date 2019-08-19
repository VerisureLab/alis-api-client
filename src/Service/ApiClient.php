<?php

namespace VerisureLab\Library\AlisApiClient\Service;

class ApiClient
{
    /**
     * @var
     */
    private $clients;

    public function __construct()
    {
        $this->clients = [];
    }

    public function addClient(string $alias, ApiClientInterface $apiClient): void
    {
        $this->clients[$alias] = $apiClient;
    }

    public function blacklist(): ClientBlacklist
    {
        return $this->clients['blacklist'];
    }

    public function channel(): ClientChannel
    {
        return $this->clients['channel'];
    }

    public function lead(): ClientLead
    {
        return $this->clients['lead'];
    }

    public function source(): ClientSource
    {
        return $this->clients['source'];
    }

    public function supplier(): ClientSupplier
    {
        return $this->clients['supplier'];
    }
}