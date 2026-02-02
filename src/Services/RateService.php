<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Rate;

/**
 * Service for managing PayMongo Rates.
 */
class RateService extends BaseService
{
    private const URI = '/rates';

    /**
     * Retrieve a rate by ID.
     */
    public function retrieve(string $id): Rate
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Rate($apiResource);
    }

    /**
     * Search for rates.
     *
     * @param array<string, mixed> $params
     */
    public function search(array $params): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new Rate($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
