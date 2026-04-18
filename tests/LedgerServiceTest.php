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
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ledgerResource('ldg_test_123', 'Test Ledger'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $ledger = $client->ledgers->create(['name' => 'Test Ledger', 'currency' => 'PHP']);

        $this->assertSame('ldg_test_123', $ledger->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers', $fake->lastRequest['url']);
    }

    public function testRetrieveLedgerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ledgerResource('ldg_test_123', 'Test Ledger'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $ledger = $client->ledgers->retrieve('ldg_test_123');

        $this->assertSame('ldg_test_123', $ledger->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers/ldg_test_123', $fake->lastRequest['url']);
    }

    public function testListLedgersBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'ldg_test_123',
                'attributes' => $this->ledgerAttributes('Test Ledger'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->ledgers->all(['limit' => 10]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers', $fake->lastRequest['url']);
    }

    public function testUpdateLedgerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ledgerResource('ldg_test_123', 'Updated Ledger'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $ledger = $client->ledgers->update('ldg_test_123', ['name' => 'Updated Ledger']);

        $this->assertSame('Updated Ledger', $ledger->name);
        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers/ldg_test_123', $fake->lastRequest['url']);
    }

    public function testDeleteLedgerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ledgerResource('ldg_test_123', 'Test Ledger'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $ledger = $client->ledgers->delete('ldg_test_123');

        $this->assertSame('ldg_test_123', $ledger->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers/ldg_test_123', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function ledgerAttributes(string $name): array
    {
        return [
            'currency' => 'PHP',
            'description' => 'Test',
            'livemode' => false,
            'name' => $name,
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function ledgerResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->ledgerAttributes($name),
            ],
        ]);
    }
}
