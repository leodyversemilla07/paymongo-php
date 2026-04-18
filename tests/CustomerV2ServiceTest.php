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
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->customerResource('cus_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $customer = $client->customersV2->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->assertSame('cus_test_123', $customer->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/customers', $fake->lastRequest['url']);
    }

    public function testListCustomersV2BuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'cus_test_123',
                'attributes' => $this->customerAttributes('test@example.com'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $customers = $client->customersV2->all(['limit' => 5]);

        $this->assertCount(1, $customers->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/customers', $fake->lastRequest['url']);
        $this->assertSame(5, $fake->lastRequest['params']['limit']);
    }

    public function testUpdateCustomerV2BuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->customerResource('cus_test_123', 'updated@example.com'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $customer = $client->customersV2->update('cus_test_123', [
            'email' => 'updated@example.com',
        ]);

        $this->assertSame('updated@example.com', $customer->email);
        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/customers/cus_test_123', $fake->lastRequest['url']);
    }

    public function testCustomerV2UsesBearerTokenWhenConfigured(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->customerResource('cus_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
            'customer_bearer_token' => 'jwt_customer_token',
        ]);

        $client->customersV2->retrieve('cus_test_123');

        $this->assertSame(['Authorization: Bearer jwt_customer_token'], $fake->lastRequest['headers']);
        $this->assertSame('https://api.paymongo.com/v2/customers/cus_test_123', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function customerAttributes(string $email): array
    {
        return [
            'default_device' => null,
            'default_payment_method_id' => null,
            'email' => $email,
            'first_name' => 'Test',
            'last_name' => 'User',
            'livemode' => false,
            'organization_id' => null,
            'phone' => '1234567890',
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function customerResource(string $id, string $email): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->customerAttributes($email),
            ],
        ]);
    }
}
