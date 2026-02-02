<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LedgerEntryServiceTest extends TestCase
{
    public function testUpdateLedgerEntryBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'le_test_123',
                'attributes' => [
                    'account_id' => 'acct_123',
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'entry_type' => 'credit',
                    'livemode' => false,
                    'reference_number' => 'ref_123',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->ledgerEntries->update('le_test_123', [
            'metadata' => ['key' => 'value']
        ]);

        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_entries/le_test_123', $fake->lastRequest['url']);
        $this->assertSame('value', $fake->lastRequest['params']['metadata']['key']);
    }
}
