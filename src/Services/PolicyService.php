<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Policy;
use Paymongo\Entities\PolicyEvaluation;

/**
 * Service for managing PayMongo Policies.
 */
class PolicyService extends BaseService
{
    private const URI = '/policies';

    /**
     * List all policies.
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
            $objects[] = new Policy($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Retrieve a policy by ID.
     */
    public function retrieve(string $id): Policy
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Policy($apiResource);
    }

    /**
     * Create a new policy.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Policy
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Policy($apiResource);
    }

    /**
     * Update a policy.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Policy
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Policy($apiResource);
    }

    /**
     * Evaluate policies.
     *
     * @param array<string, mixed> $params
     */
    public function evaluate(array $params): PolicyEvaluation
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl('/policies/evaluate'),
            'params' => $params
        ]);

        return new PolicyEvaluation($apiResource);
    }
}
