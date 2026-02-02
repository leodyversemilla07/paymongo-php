<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CustomerPaymentMethodServiceTest extends TestCase
{
    public function testListCustomerPaymentMethodsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => []
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->customers->paymentMethods('cus_test_123');

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123/payment_methods', $fake->lastRequest['url']);
    }

    public function testDeleteCustomerPaymentMethodBuildsRequest(): void
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

        $client->customers->deletePaymentMethod('cus_test_123', 'pm_test_123');

        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/customers/cus_test_123/payment_methods/pm_test_123', $fake->lastRequest['url']);
    }
}
