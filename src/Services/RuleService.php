<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Rule;

/**
 * Service for managing PayMongo Rules.
 */
class RuleService extends BaseService
{
    private const URI = '/rules';

    /**
     * Create a new rule.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Rule
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Rule($apiResource);
    }

    /**
     * Retrieve a rule by ID.
     */
    public function retrieve(string $id): Rule
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Rule($apiResource);
    }

    /**
     * List all rules.
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
            $objects[] = new Rule($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Update a rule.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Rule
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Rule($apiResource);
    }

    /**
     * Delete a rule.
     */
    public function delete(string $id): Rule
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Rule($apiResource);
    }
}
