<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Subscription;

/**
 * Service for managing PayMongo Subscriptions.
 */
class SubscriptionService extends BaseService
{
    private const URI = '/subscriptions';

    /**
     * Create a new subscription.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Subscription($apiResource);
    }

    /**
     * Retrieve a subscription by ID.
     */
    public function retrieve(string $id): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Subscription($apiResource);
    }

    /**
     * List all subscriptions.
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
            $objects[] = new Subscription($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(string $id): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/cancel"),
        ]);

        return new Subscription($apiResource);
    }

    /**
     * Update a subscription's plan.
     *
     * @param array<string, mixed> $params
     */
    public function updatePlan(string $id, array $params): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}/plan"),
            'params' => $params
        ]);

        return new Subscription($apiResource);
    }

    /**
     * Update a subscription's payment method.
     *
     * @param array<string, mixed> $params
     */
    public function updatePaymentMethod(string $id, array $params): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}/payment_method"),
            'params' => $params
        ]);

        return new Subscription($apiResource);
    }

    /**
     * Trigger a subscription billing cycle.
     *
     * @param array<string, mixed> $params
     */
    public function triggerCycle(string $id, array $params = []): Subscription
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/trigger"),
            'params' => $params
        ]);

        return new Subscription($apiResource);
    }
}
