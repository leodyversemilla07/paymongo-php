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
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'pm_test_123',
                'attributes' => [
                    'type' => 'card',
                    'billing' => null,
                    'details' => ['last4' => '4242'],
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->paymentMethods->create([
            'type' => 'card',
            'details' => [
                'card_number' => '4343434343434345',
                'exp_month' => 12,
                'exp_year' => 30
            ],
            'billing' => [
                'name' => 'Test User',
                'email' => 'test@example.com'
            ]
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_methods', $fake->lastRequest['url']);
        $this->assertSame('card', $fake->lastRequest['params']['type']);
    }
}
