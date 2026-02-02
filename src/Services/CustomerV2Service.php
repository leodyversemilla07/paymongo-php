<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Customer;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Customers (API v2).
 */
class CustomerV2Service extends BaseService
{
    private const URI = '/customers';

    /**
     * Create a new customer.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => "{$this->client->apiBaseUrl}/v2" . self::URI,
            'params' => $params
        ]);

        return new Customer($apiResource);
    }

    /**
     * List all customers.
     *
     * @param array<string, mixed> $params
     */
    public function all(array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => "{$this->client->apiBaseUrl}/v2" . self::URI,
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new Customer($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Retrieve a customer by ID.
     */
    public function retrieve(string $id): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => "{$this->client->apiBaseUrl}/v2" . self::URI . "/{$id}",
        ]);

        return new Customer($apiResource);
    }

    /**
     * Update a customer.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => "{$this->client->apiBaseUrl}/v2" . self::URI . "/{$id}",
            'params' => $params
        ]);

        return new Customer($apiResource);
    }
}
