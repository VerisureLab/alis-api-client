<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\ValueObject\Channel;
use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;

/**
 * Only for ADMIN users
 */
class ClientChannel extends AbstractApiClient
{
    public function create(Channel $channel, ?Credentials $credentials = null): string
    {
        return $this->callCreate([
            'name' => $channel->getName(),
            'code' => $channel->getCode(),
        ], $credentials);
    }

    public function update(string $id, Channel $channel, ?Credentials $credentials = null): array
    {
        return $this->callUpdate($id, [
            'name' => $channel->getName(),
            'code' => $channel->getCode(),
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
        return 'channels';
    }
}