<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LedgerServiceTest extends TestCase
{
    public function testCreateLedgerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'ldg_test_123',
                'attributes' => [
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'livemode' => false,
                    'name' => 'Test Ledger',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->ledgers->create([
            'name' => 'Test Ledger',
            'currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers', $fake->lastRequest['url']);
        $this->assertSame('Test Ledger', $fake->lastRequest['params']['name']);
    }
}
