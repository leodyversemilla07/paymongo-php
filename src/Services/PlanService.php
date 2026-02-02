<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Plan;

/**
 * Service for managing PayMongo Plans.
 */
class PlanService extends BaseService
{
    private const URI = '/plans';

    /**
     * Create a new plan.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Plan
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Plan($apiResource);
    }

    /**
     * Retrieve a plan by ID.
     */
    public function retrieve(string $id): Plan
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Plan($apiResource);
    }

    /**
     * Update a plan.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Plan
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Plan($apiResource);
    }

    /**
     * List all plans.
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
            $objects[] = new Plan($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
