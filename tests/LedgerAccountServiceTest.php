<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LedgerAccountServiceTest extends TestCase
{
    public function testCreateLedgerAccountBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'acct_test_123',
                'attributes' => [
                    'account_code' => '1000',
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'livemode' => false,
                    'name' => 'Cash',
                    'type' => 'asset',
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

        $client->ledgerAccounts->create([
            'name' => 'Cash',
            'currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts', $fake->lastRequest['url']);
        $this->assertSame('Cash', $fake->lastRequest['params']['name']);
    }
}
