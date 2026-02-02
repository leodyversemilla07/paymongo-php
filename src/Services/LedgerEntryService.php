<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerEntry;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Ledger Entries.
 */
class LedgerEntryService extends BaseService
{
    private const URI = '/ledger_entries';

    /**
     * Retrieve a ledger entry by ID.
     */
    public function retrieve(string $id): LedgerEntry
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new LedgerEntry($apiResource);
    }

    /**
     * List all ledger entries.
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
            $objects[] = new LedgerEntry($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a ledger entry.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): LedgerEntry
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new LedgerEntry($apiResource);
    }
}
