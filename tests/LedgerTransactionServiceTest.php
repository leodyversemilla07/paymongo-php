<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LedgerTransactionServiceTest extends TestCase
{
    public function testReverseLedgerTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'lt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'entry_type' => 'debit',
                    'livemode' => false,
                    'reference_number' => 'ref_123',
                    'status' => 'posted',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->ledgerTransactions->reverse('lt_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions/lt_test_123/reverse', $fake->lastRequest['url']);
    }
}
