<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Transfer;

/**
 * Service for managing PayMongo Legacy Transfers.
 */
class LegacyTransferService extends BaseService
{
    private const URI = '/transfers';

    /**
     * Create a batch transfer.
     *
     * @param array<string, mixed> $params
     */
    public function createBatch(array $params): Transfer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Transfer($apiResource);
    }

    /**
     * Retrieve a batch transfer by ID.
     */
    public function retrieveBatch(string $id): Transfer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Transfer($apiResource);
    }

    /**
     * List all transfers.
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
            $objects[] = new Transfer($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
