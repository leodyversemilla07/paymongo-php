<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Requirement;

/**
 * Service for managing PayMongo Requirements.
 */
class RequirementService extends BaseService
{
    private const URI = '/requirements';

    /**
     * Retrieve requirements for a child merchant.
     */
    public function retrieveChildMerchant(string $id): Requirement
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl("/child_merchants/{$id}/requirements"),
        ]);

        return new Requirement($apiResource);
    }

    /**
     * Retrieve requirements for a consumer.
     */
    public function retrieveConsumer(string $id): Requirement
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl("/consumers/{$id}/requirements"),
        ]);

        return new Requirement($apiResource);
    }
}
