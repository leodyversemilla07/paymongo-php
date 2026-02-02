<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Wallet;

/**
 * Service for managing PayMongo Wallets (API v2).
 */
class WalletV2Service extends BaseService
{
    private const URI = '/wallets';

    /**
     * Retrieve a wallet by ID.
     */
    public function retrieve(string $id): Wallet
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => "{$this->client->apiBaseUrl}/v2" . self::URI . "/{$id}",
        ]);

        return new Wallet($apiResource);
    }
}
