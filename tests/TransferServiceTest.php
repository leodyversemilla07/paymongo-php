<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class TransferServiceTest extends TestCase
{
    public function testCreateBatchTransferBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'tr_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'livemode' => false,
                    'reference_number' => 'ref_123',
                    'status' => 'pending',
                    'recipient' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->transfers->createBatch([
            'amount' => 1000,
            'currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }
}
