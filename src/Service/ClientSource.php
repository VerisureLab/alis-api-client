<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;
use VerisureLab\Library\AlisApiClient\ValueObject\Source;

/**
 * Only for ADMIN users
 */
class ClientSource extends AbstractApiClient
{
    public function create(Source $source, ?Credentials $credentials = null): string
    {
        return $this->callCreate([
            'name' => $source->getName(),
            'supplier' => '/suppliers/'.$source->getSupplierId(),
            'channel' => '/channels/'.$source->getChannelId(),
        ], $credentials);
    }

    public function update(string $id, Source $source, ?Credentials $credentials = null): array
    {
        return $this->callUpdate($id, [
            'name' => $source->getName(),
            'supplier' => '/suppliers/'.$source->getSupplierId(),
            'channel' => '/channels/'.$source->getChannelId(),
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

    public function delete(string $id, ?Credentials $credentials = null): void
    {
        $this->callDelete($id, $credentials);
    }

    protected function getResource(): string
    {
        return 'sources';
    }
}