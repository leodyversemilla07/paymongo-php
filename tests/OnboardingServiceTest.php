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
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'cm_test_123',
                'attributes' => [
                    'business' => [],
                    'livemode' => false,
                    'status' => 'pending',
                    'requirements' => [],
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->childMerchants->create([
            'business' => [
                'name' => 'Test Business'
            ]
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/child_merchants', $fake->lastRequest['url']);
        $this->assertSame('Test Business', $fake->lastRequest['params']['business']['name']);
    }
}
