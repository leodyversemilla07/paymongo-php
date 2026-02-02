<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Score;

/**
 * Service for managing PayMongo Scores.
 */
class ScoreService extends BaseService
{
    private const URI = '/scores';

    /**
     * Create a new score.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Score
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Score($apiResource);
    }

    /**
     * Retrieve a score by ID.
     */
    public function retrieve(string $id): Score
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Score($apiResource);
    }

    /**
     * List all scores.
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
            $objects[] = new Score($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
