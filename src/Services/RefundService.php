<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Refund;

/**
 * Service for managing PayMongo Refunds.
 */
class RefundService extends BaseService
{
    private const URI = '/refunds';

    /**
     * List all refunds.
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
            $objects[] = new Refund($rowApiResource);
        }

        return new Listing([
            'has_more' => $apiResponse->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Retrieve a refund by ID.
     */
    public function retrieve(string $id): Refund
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Refund($apiResource);
    }

    /**
     * Create a new refund.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Refund
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Refund($apiResource);
    }
}
