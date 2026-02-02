<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\PaymentIntent;

/**
 * Service for managing PayMongo Payment Intents.
 */
class PaymentIntentService extends BaseService
{
    private const URI = '/payment_intents';

    /**
     * Create a new payment intent.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): PaymentIntent
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new PaymentIntent($apiResource);
    }

    /**
     * Retrieve a payment intent by ID.
     */
    public function retrieve(string $id): PaymentIntent
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new PaymentIntent($apiResource);
    }

    /**
     * Capture a payment intent.
     *
     * @param array<string, mixed> $params
     */
    public function capture(string $id, array $params): PaymentIntent
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/capture"),
            'params' => $params
        ]);

        return new PaymentIntent($apiResource);
    }

    /**
     * Cancel a payment intent.
     */
    public function cancel(string $id): PaymentIntent
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/cancel"),
        ]);

        return new PaymentIntent($apiResource);
    }
}
