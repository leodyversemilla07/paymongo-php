<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CustomerPaymentMethodServiceTest extends TestCase
{
    public function testCreateCustomerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->customerResource('cus_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $customer = $client->customers->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
        ]);

        $this->assertSame('cus_test_123', $customer->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers', $fake->lastRequest['url']);
        $this->assertSame('test@example.com', $fake->lastRequest['params']['email']);
    }

    public function testRetrieveCustomerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->customerResource('cus_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $customer = $client->customers->retrieve('cus_test_123');

        $this->assertSame('cus_test_123', $customer->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123', $fake->lastRequest['url']);
    }

    public function testUpdateCustomerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->customerResource('cus_test_123', 'updated@example.com'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $customer = $client->customers->update('cus_test_123', [
            'email' => 'updated@example.com',
        ]);

        $this->assertSame('updated@example.com', $customer->email);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123', $fake->lastRequest['url']);
        $this->assertSame('updated@example.com', $fake->lastRequest['params']['email']);
    }

    public function testDeleteCustomerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->customerResource('cus_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $customer = $client->customers->delete('cus_test_123');

        $this->assertSame('cus_test_123', $customer->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123', $fake->lastRequest['url']);
    }

    public function testListCustomerPaymentMethodsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'pm_test_123',
                    'attributes' => $this->paymentMethodAttributes(),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $methods = $client->customers->paymentMethods('cus_test_123');

        $this->assertCount(1, $methods->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123/payment_methods', $fake->lastRequest['url']);
    }

    public function testDeleteCustomerPaymentMethodBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'pm_test_123',
                'attributes' => $this->paymentMethodAttributes(),
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $paymentMethod = $client->customers->deletePaymentMethod('cus_test_123', 'pm_test_123');

        $this->assertSame('pm_test_123', $paymentMethod->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123/payment_methods/pm_test_123', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
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
            'phone' => null,
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

    /**
     * @return array<string, mixed>
     */
    private function paymentMethodAttributes(): array
    {
        return [
            'type' => 'card',
            'billing' => null,
            'details' => ['last4' => '4242'],
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }
}
