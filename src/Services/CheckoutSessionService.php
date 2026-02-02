<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\CheckoutSession;

/**
 * Service for managing PayMongo Checkout Sessions.
 */
class CheckoutSessionService extends BaseService
{
    private const URI = '/checkout_sessions';

    /**
     * Create a new checkout session.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): CheckoutSession
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new CheckoutSession($apiResource);
    }

    /**
     * Retrieve a checkout session by ID.
     */
    public function retrieve(string $id): CheckoutSession
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new CheckoutSession($apiResource);
    }

    /**
     * Expire a checkout session.
     */
    public function expire(string $id): CheckoutSession
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/expire"),
        ]);

        return new CheckoutSession($apiResource);
    }
}
