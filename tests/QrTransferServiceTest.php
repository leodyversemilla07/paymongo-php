<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class QrTransferServiceTest extends TestCase
{
    public function testGenerateQrTransferBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->qrTransferResource('qr_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->qrTransfers->generate(['amount' => 1000, 'currency' => 'PHP']);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/qr', $fake->lastRequest['url']);
    }

    public function testExecuteQrTransferBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->qrTransferResource('qr_test_123', 'executed'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $transfer = $client->qrTransfers->execute(['reference_number' => 'ref_123']);

        $this->assertSame('executed', $transfer->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/qr/execute', $fake->lastRequest['url']);
    }

    private function qrTransferResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'reference_number' => 'ref_123',
                    'status' => $status,
                    'recipient' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }
}
