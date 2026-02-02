<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Consumer;

/**
 * Service for managing PayMongo Consumers.
 */
class ConsumerService extends BaseService
{
    private const URI = '/consumers';

    /**
     * Create a new consumer.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Consumer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Consumer($apiResource);
    }

    /**
     * Update a consumer.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Consumer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PATCH',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Consumer($apiResource);
    }

    /**
     * Submit a consumer for review.
     *
     * @param array<string, mixed> $params
     */
    public function submit(string $id, array $params = []): Consumer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/submit"),
            'params' => $params
        ]);

        return new Consumer($apiResource);
    }
}
