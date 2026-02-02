<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CardholderServiceTest extends TestCase
{
    public function testCreateCardholderBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'chd_test_123',
                'attributes' => [
                    'billing' => null,
                    'email' => 'test@example.com',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'livemode' => false,
                    'phone' => '1234567890',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->cardholders->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cardholders', $fake->lastRequest['url']);
        $this->assertSame('test@example.com', $fake->lastRequest['params']['email']);
    }
}
