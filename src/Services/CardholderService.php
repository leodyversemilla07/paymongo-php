<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Cardholder;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Cardholders.
 */
class CardholderService extends BaseService
{
    private const URI = '/cardholders';

    /**
     * Create a new cardholder.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Cardholder
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Cardholder($apiResource);
    }

    /**
     * Retrieve a cardholder by ID.
     */
    public function retrieve(string $id): Cardholder
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Cardholder($apiResource);
    }

    /**
     * List all cardholders.
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
            $objects[] = new Cardholder($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
