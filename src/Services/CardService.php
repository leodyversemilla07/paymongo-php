<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Card;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Cards.
 */
class CardService extends BaseService
{
    private const URI = '/cards';

    /**
     * Create a new card.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Card
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Card($apiResource);
    }

    /**
     * Retrieve a card by ID.
     */
    public function retrieve(string $id): Card
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Card($apiResource);
    }

    /**
     * List all cards.
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
            $objects[] = new Card($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Activate a card.
     *
     * @param array<string, mixed> $params
     */
    public function activate(string $id, array $params = []): Card
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/activate"),
            'params' => $params
        ]);

        return new Card($apiResource);
    }

    /**
     * Update a card.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Card
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Card($apiResource);
    }
}
