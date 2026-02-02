<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\PaymentMethod;

/**
 * Service for managing PayMongo Payment Methods.
 */
class PaymentMethodService extends BaseService
{
    private const URI = '/payment_methods';

    /**
     * Create a new payment method.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): PaymentMethod
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new PaymentMethod($apiResource);
    }

    /**
     * Retrieve a payment method by ID.
     */
    public function retrieve(string $id): PaymentMethod
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new PaymentMethod($apiResource);
    }
}
