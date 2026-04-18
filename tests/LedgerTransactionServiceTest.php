<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LedgerTransactionServiceTest extends TestCase
{
    public function testCreateLedgerTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->txResource('lt_test_123', 'posted'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $tx = $client->ledgerTransactions->create(['amount' => 1000, 'currency' => 'PHP']);

        $this->assertSame('lt_test_123', $tx->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions', $fake->lastRequest['url']);
    }

    public function testRetrieveLedgerTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->txResource('lt_test_123', 'posted'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $tx = $client->ledgerTransactions->retrieve('lt_test_123');

        $this->assertSame('lt_test_123', $tx->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions/lt_test_123', $fake->lastRequest['url']);
    }

    public function testListLedgerTransactionsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'lt_test_123',
                'attributes' => $this->txAttributes('posted'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->ledgerTransactions->all(['limit' => 10]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions', $fake->lastRequest['url']);
    }

    public function testUpdateLedgerTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->txResource('lt_test_123', 'posted'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->ledgerTransactions->update('lt_test_123', ['description' => 'Updated']);

        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions/lt_test_123', $fake->lastRequest['url']);
    }

    public function testReverseLedgerTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->txResource('lt_test_123', 'posted'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->ledgerTransactions->reverse('lt_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions/lt_test_123/reverse', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function txAttributes(string $status): array
    {
        return [
            'amount' => 1000,
            'currency' => 'PHP',
            'description' => 'Test',
            'entry_type' => 'debit',
            'livemode' => false,
            'reference_number' => 'ref_123',
            'status' => $status,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function txResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->txAttributes($status),
            ],
        ]);
    }
}
