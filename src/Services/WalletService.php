<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Wallet;

/**
 * Service for managing PayMongo Wallets.
 */
class WalletService extends BaseService
{
    private const URI = '/wallets';

    /**
     * Retrieve a wallet by ID.
     */
    public function retrieve(string $id): Wallet
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Wallet($apiResource);
    }
}
