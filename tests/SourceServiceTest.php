<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class SourceServiceTest extends TestCase
{
    public function testCreateSourceBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse($this->sourceResource('src_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $source = $client->sources->create([
            'type' => 'gcash',
            'amount' => 10000,
            'currency' => 'PHP',
        ]);

        $this->assertSame('src_test_123', $source->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/sources', $fake->lastRequest['url']);
        $this->assertSame('gcash', $fake->lastRequest['params']['type']);
    }

    public function testRetrieveSourceBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse($this->sourceResource('src_test_123', 'chargeable'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $source = $client->sources->retrieve('src_test_123');

        $this->assertSame('src_test_123', $source->id);
        $this->assertSame('chargeable', $source->status);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/sources/src_test_123', $fake->lastRequest['url']);
    }

    private function sourceResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'type' => 'gcash',
                    'amount' => 10000,
                    'currency' => 'PHP',
                    'description' => 'Test source',
                    'livemode' => false,
                    'status' => $status,
                    'redirect' => [
                        'checkout_url' => 'https://checkout.example.com/test',
                        'failed' => 'https://example.com/failed',
                        'success' => 'https://example.com/success',
                    ],
                    'billing' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }
}
