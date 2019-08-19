<?php

namespace VerisureLab\Library\AlisApiClient\Service;

use VerisureLab\Library\AlisApiClient\ValueObject\Credentials;
use VerisureLab\Library\AlisApiClient\ValueObject\Supplier;

/**
 * Only for ADMIN users
 */
class ClientSupplier extends AbstractApiClient
{
    public function create(Supplier $supplier, ?Credentials $credentials = null): string
    {
        return $this->callCreate([
            'name' => $supplier->getName(),
            'code' => $supplier->getCode(),
            'client' => $supplier->getClient(),
        ], $credentials);
    }

    public function update(string $id, Supplier $supplier, ?Credentials $credentials = null): array
    {
        return $this->callUpdate($id, [
            'name' => $supplier->getName(),
            'code' => $supplier->getCode(),
            'client' => $supplier->getClient(),
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
        return 'suppliers';
    }
}