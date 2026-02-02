<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LegacyWalletTransactionServiceTest extends TestCase
{
    public function testCreateLegacyWalletTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'livemode' => false,
                    'reference_number' => 'ref_123',
                    'status' => 'pending',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->legacyWalletTransactions->create([
            'amount' => 1000,
            'currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_transactions', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }
}
