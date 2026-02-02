<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WalletV2ServiceTest extends TestCase
{
    public function testRetrieveWalletV2BuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'wallet_test_123',
                'attributes' => [
                    'available_balance' => 1000,
                    'balance' => 1000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'name' => 'Wallet',
                    'type' => 'main',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->walletsV2->retrieve('wallet_test_123');

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/wallets/wallet_test_123', $fake->lastRequest['url']);
    }
}
