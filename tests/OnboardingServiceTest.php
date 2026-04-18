<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class OnboardingServiceTest extends TestCase
{
    public function testCreateChildMerchantBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->childMerchantResource('cm_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $merchant = $client->childMerchants->create([
            'business' => [
                'name' => 'Test Business',
            ],
        ]);

        $this->assertSame('cm_test_123', $merchant->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children', $fake->lastRequest['url']);
        $this->assertSame('Test Business', $fake->lastRequest['params']['business']['name']);
    }

    public function testListChildMerchantsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'cm_test_123',
                    'attributes' => $this->childMerchantAttributes('pending'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $merchants = $client->childMerchants->all([
            'limit' => 10,
        ]);

        $this->assertCount(1, $merchants->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }

    public function testUpdateChildMerchantBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->childMerchantResource('cm_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $merchant = $client->childMerchants->update('cm_test_123', [
            'business' => [
                'name' => 'Updated Business',
            ],
        ]);

        $this->assertSame('cm_test_123', $merchant->id);
        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123', $fake->lastRequest['url']);
        $this->assertSame('Updated Business', $fake->lastRequest['params']['business']['name']);
    }

    public function testSubmitChildMerchantBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->childMerchantResource('cm_test_123', 'submitted'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $merchant = $client->childMerchants->submit('cm_test_123', [
            'remarks' => 'Ready for review',
        ]);

        $this->assertSame('submitted', $merchant->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/submit', $fake->lastRequest['url']);
        $this->assertSame('Ready for review', $fake->lastRequest['params']['remarks']);
    }

    /**
     * @return array<string, mixed>
     */
    private function childMerchantAttributes(string $status): array
    {
        return [
            'business' => [
                'name' => 'Test Business',
            ],
            'livemode' => false,
            'status' => $status,
            'requirements' => [],
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function childMerchantResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->childMerchantAttributes($status),
            ],
        ]);
    }
}
