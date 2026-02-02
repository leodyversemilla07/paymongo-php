<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Review;

/**
 * Service for managing PayMongo Reviews.
 */
class ReviewService extends BaseService
{
    private const URI = '/reviews';

    /**
     * Retrieve a review by ID.
     */
    public function retrieve(string $id): Review
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Review($apiResource);
    }

    /**
     * List all reviews.
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
            $objects[] = new Review($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Approve a review.
     *
     * @param array<string, mixed> $params
     */
    public function approve(string $id, array $params = []): Review
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}/approve"),
            'params' => $params
        ]);

        return new Review($apiResource);
    }
}
