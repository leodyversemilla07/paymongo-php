<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\ReceivingInstitution;
use Paymongo\Entities\WalletTransaction;

/**
 * Service for managing PayMongo Legacy Wallet Transactions.
 */
class LegacyWalletTransactionService extends BaseService
{
    private const URI = '/wallet_transactions';

    /**
     * List all wallet transactions.
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
            $objects[] = new WalletTransaction($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Create a new wallet transaction.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): WalletTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new WalletTransaction($apiResource);
    }

    /**
     * Retrieve a wallet transaction by ID.
     */
    public function retrieve(string $id): WalletTransaction
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new WalletTransaction($apiResource);
    }

    /**
     * List all receiving institutions.
     *
     * @param array<string, mixed> $params
     */
    public function receivingInstitutions(array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl('/receiving_institutions'),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new ReceivingInstitution($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
