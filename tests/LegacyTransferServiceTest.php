<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LegacyTransferServiceTest extends TestCase
{
    public function testCreateLegacyTransferBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->transferResource('lt_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->legacyTransfers->createBatch(['amount' => 1000, 'currency' => 'PHP']);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers', $fake->lastRequest['url']);
    }

    public function testRetrieveLegacyTransferBatchBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->transferResource('lt_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $transfer = $client->legacyTransfers->retrieveBatch('lt_test_123');

        $this->assertSame('lt_test_123', $transfer->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers/lt_test_123', $fake->lastRequest['url']);
    }

    public function testListLegacyTransfersBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'lt_test_123',
                'attributes' => $this->transferAttributes(),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->legacyTransfers->all(['limit' => 10]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function transferAttributes(): array
    {
        return [
            'amount' => 1000,
            'currency' => 'PHP',
            'description' => 'Test',
            'livemode' => false,
            'reference_number' => 'ref_123',
            'status' => 'pending',
            'recipient' => null,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function transferResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->transferAttributes(),
            ],
        ]);
    }
}
