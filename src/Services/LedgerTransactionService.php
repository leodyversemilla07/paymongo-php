<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerTransaction;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Ledger Transactions.
 */
class LedgerTransactionService extends BaseService
{
    private const URI = '/ledger_transactions';

    /**
     * Create a new ledger transaction.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): LedgerTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new LedgerTransaction($apiResource);
    }

    /**
     * Retrieve a ledger transaction by ID.
     */
    public function retrieve(string $id): LedgerTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new LedgerTransaction($apiResource);
    }

    /**
     * List all ledger transactions.
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
            $objects[] = new LedgerTransaction($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a ledger transaction.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): LedgerTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new LedgerTransaction($apiResource);
    }

    /**
     * Reverse a ledger transaction.
     *
     * @param array<string, mixed> $params
     */
    public function reverse(string $id, array $params = []): LedgerTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/reverse"),
            'params' => $params
        ]);

        return new LedgerTransaction($apiResource);
    }
}
