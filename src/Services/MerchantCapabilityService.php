<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\MerchantCapability;

/**
 * Service for managing PayMongo Merchant Capabilities.
 */
class MerchantCapabilityService extends BaseService
{
    private const URI = '/merchant_capabilities';

    /**
     * List all merchant capabilities.
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
            $objects[] = new MerchantCapability($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }
}
