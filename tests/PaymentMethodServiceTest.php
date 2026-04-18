<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PaymentMethodServiceTest extends TestCase
{
    public function testCreatePaymentMethodBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->paymentMethodResource('pm_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $method = $client->paymentMethods->create([
            'type' => 'card',
            'details' => [
                'card_number' => '4343434343434345',
                'exp_month' => 12,
                'exp_year' => 30,
            ],
        ]);

        $this->assertSame('pm_test_123', $method->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_methods', $fake->lastRequest['url']);
    }

    public function testRetrievePaymentMethodBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->paymentMethodResource('pm_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $method = $client->paymentMethods->retrieve('pm_test_123');

        $this->assertSame('pm_test_123', $method->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_methods/pm_test_123', $fake->lastRequest['url']);
    }

    public function testUpdatePaymentMethodBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->paymentMethodResource('pm_test_123', ['order_id' => 'order_123']));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->paymentMethods->update('pm_test_123', [
            'metadata' => ['order_id' => 'order_123'],
        ]);

        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_methods/pm_test_123', $fake->lastRequest['url']);
    }

    private function paymentMethodResource(string $id, ?array $metadata = null): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'type' => 'card',
                    'billing' => null,
                    'details' => ['last4' => '4242'],
                    'metadata' => $metadata,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }
}
