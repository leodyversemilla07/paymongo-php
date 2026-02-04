<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore2Test extends TestCase
{
    public function testWebhookCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wh_test_123',
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.paid'],
                    'url' => 'https://example.com/webhook',
                    'status' => 'enabled',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $webhook = $client->webhooks->create([
            'url' => 'https://example.com/webhook',
            'events' => ['payment.paid'],
        ]);

        $this->assertSame('wh_test_123', $webhook->id);
        $this->assertSame('https://example.com/webhook', $webhook->url);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks', $fake->lastRequest['url']);
    }

    public function testCustomerV2CreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'cus_test_123',
                'attributes' => [
                    'email' => 'test@example.com',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'livemode' => false,
                    'organization_id' => null,
                    'phone' => null,
                    'default_device' => null,
                    'default_payment_method_id' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $customer = $client->customersV2->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->assertSame('cus_test_123', $customer->id);
        $this->assertSame('test@example.com', $customer->email);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/customers', $fake->lastRequest['url']);
    }

    public function testPlanCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'plan_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'interval' => 'month',
                    'interval_count' => 1,
                    'livemode' => false,
                    'name' => 'Basic',
                    'billing_statement_descriptor' => null,
                    'description' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $plan = $client->plans->create([
            'name' => 'Basic',
            'amount' => 1000,
            'currency' => 'PHP',
            'interval' => 'month',
        ]);

        $this->assertSame('plan_test_123', $plan->id);
        $this->assertSame('Basic', $plan->name);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans', $fake->lastRequest['url']);
    }
}
