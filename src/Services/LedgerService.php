<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Ledger;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Ledgers.
 */
class LedgerService extends BaseService
{
    private const URI = '/ledgers';

    /**
     * Create a new ledger.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Ledger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Ledger($apiResource);
    }

    /**
     * Retrieve a ledger by ID.
     */
    public function retrieve(string $id): Ledger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Ledger($apiResource);
    }

    /**
     * List all ledgers.
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
            $objects[] = new Ledger($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a ledger.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Ledger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Ledger($apiResource);
    }

    /**
     * Delete a ledger.
     */
    public function delete(string $id): Ledger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Ledger($apiResource);
    }
}
