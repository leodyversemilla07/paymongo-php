<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Trigger;

/**
 * Service for managing PayMongo Triggers.
 */
class TriggerService extends BaseService
{
    private const URI = '/triggers';

    /**
     * Create a new trigger.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Trigger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Trigger($apiResource);
    }

    /**
     * Retrieve a trigger by ID.
     */
    public function retrieve(string $id): Trigger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Trigger($apiResource);
    }

    /**
     * List all triggers.
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
            $objects[] = new Trigger($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a trigger.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Trigger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Trigger($apiResource);
    }

    /**
     * Delete a trigger.
     */
    public function delete(string $id): Trigger
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Trigger($apiResource);
    }
}
