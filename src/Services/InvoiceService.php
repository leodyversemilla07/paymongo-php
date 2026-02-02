<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Invoice;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo Invoices.
 */
class InvoiceService extends BaseService
{
    private const URI = '/invoices';

    /**
     * Retrieve an invoice by ID.
     */
    public function retrieve(string $id): Invoice
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Invoice($apiResource);
    }

    /**
     * List all invoices.
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
            $objects[] = new Invoice($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Create a line item for an invoice.
     *
     * @param array<string, mixed> $params
     */
    public function createLineItem(string $id, array $params): Invoice
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/line_items"),
            'params' => $params
        ]);

        return new Invoice($apiResource);
    }

    /**
     * Pay an invoice.
     *
     * @param array<string, mixed> $params
     */
    public function pay(string $id, array $params = []): Invoice
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/pay"),
            'params' => $params
        ]);

        return new Invoice($apiResource);
    }
}
