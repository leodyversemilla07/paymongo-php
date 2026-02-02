<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\ChildMerchant;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Child Merchants.
 */
class ChildMerchantService extends BaseService
{
    private const URI = '/child_merchants';

    /**
     * Create a new child merchant.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): ChildMerchant
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new ChildMerchant($apiResource);
    }

    /**
     * List all child merchants.
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
            $objects[] = new ChildMerchant($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a child merchant.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): ChildMerchant
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new ChildMerchant($apiResource);
    }

    /**
     * Submit a child merchant for review.
     *
     * @param array<string, mixed> $params
     */
    public function submit(string $id, array $params = []): ChildMerchant
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/submit"),
            'params' => $params
        ]);

        return new ChildMerchant($apiResource);
    }
}
