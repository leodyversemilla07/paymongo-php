<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class SubscriptionServiceTest extends TestCase
{
    public function testCreateSubscriptionBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'sub_test_123',
                'attributes' => [
                    'billing' => null,
                    'cancel_at' => null,
                    'canceled_at' => null,
                    'current_period_end' => null,
                    'current_period_start' => null,
                    'customer' => null,
                    'livemode' => false,
                    'next_billing_at' => null,
                    'payment_method' => null,
                    'plan' => null,
                    'reference_number' => null,
                    'start_date' => null,
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->subscriptions->create([
            'customer' => 'cus_test_123',
            'plan' => 'plan_test_123',
            'payment_method' => 'pm_test_123'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions', $fake->lastRequest['url']);
        $this->assertSame('cus_test_123', $fake->lastRequest['params']['customer']);
    }
}
