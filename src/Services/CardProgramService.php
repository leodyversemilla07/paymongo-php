<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\CardProgram;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Card Programs.
 */
class CardProgramService extends BaseService
{
    private const URI = '/card_programs';

    /**
     * Create a new card program.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): CardProgram
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new CardProgram($apiResource);
    }

    /**
     * Retrieve a card program by ID.
     */
    public function retrieve(string $id): CardProgram
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new CardProgram($apiResource);
    }

    /**
     * List all card programs.
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
            $objects[] = new CardProgram($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
