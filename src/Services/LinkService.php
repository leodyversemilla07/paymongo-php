<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Link;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Links.
 */
class LinkService extends BaseService
{
    private const URI = '/links';

    /**
     * List all links.
     *
     * @param array<string, mixed> $params
     */
    public function all(array $params = []): Listing
    {
        $apiResponse = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        $responseData = $apiResponse->data;
        $objects = [];

        foreach ($responseData as $row) {
            $rowApiResource = new ApiResource($row);
            $objects[] = new Link($rowApiResource);
        }

        return new Listing([
            'has_more' => $apiResponse->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Retrieve a link by ID.
     */
    public function retrieve(string $id): Link
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Link($apiResource);
    }

    /**
     * Create a new link.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Link
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Link($apiResource);
    }

    /**
     * Archive a link.
     */
    public function archive(string $id): Link
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/archive"),
        ]);

        return new Link($apiResource);
    }

    /**
     * Unarchive a link.
     */
    public function unarchive(string $id): Link
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/unarchive"),
        ]);

        return new Link($apiResource);
    }
}
