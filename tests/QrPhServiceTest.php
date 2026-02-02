<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class QrPhServiceTest extends TestCase
{
    public function testCreateStaticQrPhBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'qrph_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'reference_number' => 'ref_123',
                    'description' => 'Test',
                    'livemode' => false,
                    'status' => 'active',
                    'qr_content' => 'content',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->qrPh->createStatic([
            'amount' => 1000,
            'currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/qrph', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }
}
