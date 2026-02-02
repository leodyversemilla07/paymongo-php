<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CustomerV2ServiceTest extends TestCase
{
    public function testCreateCustomerV2BuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'cus_test_123',
                'attributes' => [
                    'default_device' => null,
                    'default_payment_method_id' => null,
                    'email' => 'test@example.com',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'livemode' => false,
                    'organization_id' => null,
                    'phone' => '1234567890',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->customersV2->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/customers', $fake->lastRequest['url']);
        $this->assertSame('test@example.com', $fake->lastRequest['params']['email']);
    }
}
