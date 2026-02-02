<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Quote;

/**
 * Service for managing PayMongo Quotes.
 */
class QuoteService extends BaseService
{
    private const URI = '/quotes';

    /**
     * Create a new quote.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Quote
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Quote($apiResource);
    }

    /**
     * Retrieve a quote by ID.
     */
    public function retrieve(string $id): Quote
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Quote($apiResource);
    }
}
