<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Payment;

/**
 * Service for managing PayMongo Payments.
 */
class PaymentService extends BaseService
{
    private const URI = '/payments';

    /**
     * List all payments.
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

        $responseData = $apiResource->data;
        $objects = [];

        foreach ($responseData as $row) {
            $rowApiResource = new ApiResource($row);
            $objects[] = new Payment($rowApiResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Retrieve a payment by ID.
     */
    public function retrieve(string $id): Payment
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Payment($apiResource);
    }

    /**
     * Create a new payment.
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed> $opts
     */
    public function create(array $params, array $opts = []): Payment
    {
        $request = [
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ];
        if (!empty($opts['idempotency_key'])) {
            $request['idempotency_key'] = $opts['idempotency_key'];
        }

        $apiResource = $this->httpClient->request($request);

        return new Payment($apiResource);
    }

    /**
     * Capture a payment.
     *
     * @param array<string, mixed> $params
     */
    public function capture(string $id, array $params = []): Payment
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/capture"),
            'params' => $params
        ]);

        return new Payment($apiResource);
    }
}
