<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerAccount;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Ledger Accounts.
 */
class LedgerAccountService extends BaseService
{
    private const URI = '/ledger_accounts';

    /**
     * Create a new ledger account.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): LedgerAccount
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new LedgerAccount($apiResource);
    }

    /**
     * Retrieve a ledger account by ID.
     */
    public function retrieve(string $id): LedgerAccount
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new LedgerAccount($apiResource);
    }

    /**
     * List all ledger accounts.
     *
     * @param array<string, mixed> $params
     */
    public function all(array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new LedgerAccount($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a ledger account.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): LedgerAccount
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new LedgerAccount($apiResource);
    }

    /**
     * Delete a ledger account.
     */
    public function delete(string $id): LedgerAccount
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new LedgerAccount($apiResource);
    }
}
