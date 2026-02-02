<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Customer;
use Paymongo\Entities\Listing;
use Paymongo\Entities\PaymentMethod;

/**
 * Service for managing PayMongo Customers.
 */
class CustomerService extends BaseService
{
    private const URI = '/customers';

    /**
     * Retrieve a customer by ID.
     */
    public function retrieve(string $id): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Customer($apiResource);
    }

    /**
     * Create a new customer.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Customer($apiResource);
    }

    /**
     * Update a customer.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Customer($apiResource);
    }

    /**
     * Delete a customer.
     */
    public function delete(string $id): Customer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}")
        ]);

        return new Customer($apiResource);
    }

    /**
     * List payment methods for a customer.
     */
    public function paymentMethods(string $customerId): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$customerId}/payment_methods"),
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new PaymentMethod($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Delete a payment method from a customer.
     */
    public function deletePaymentMethod(string $customerId, string $paymentMethodId): PaymentMethod
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$customerId}/payment_methods/{$paymentMethodId}"),
        ]);

        return new PaymentMethod($apiResource);
    }
}
