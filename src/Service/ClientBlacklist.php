<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\ValueObject\Blacklist;
use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;

/**
 * Only for ADMIN users
 */
class ClientBlacklist extends AbstractApiClient
{
    public function create(Blacklist $blacklist, ?Credentials $credentials = null): string
    {
        return $this->callCreate([
            'scope' => $blacklist->getScope(),
            'value' => $blacklist->getValue(),
        ], $credentials);
    }

    public function update(string $id, Blacklist $blacklist, ?Credentials $credentials = null): array
    {
        return $this->callUpdate($id, [
            'scope' => $blacklist->getScope(),
            'value' => $blacklist->getValue(),
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
        return 'blacklists';
    }
}