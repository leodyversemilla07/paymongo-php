<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Source;

/**
 * Service for managing PayMongo Sources.
 */
class SourceService extends BaseService
{
    private const URI = '/sources';

    /**
     * Create a new source.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Source
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Source($apiResource);
    }

    /**
     * Retrieve a source by ID.
     */
    public function retrieve(string $id): Source
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Source($apiResource);
    }
}