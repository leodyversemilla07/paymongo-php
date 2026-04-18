<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class SubscriptionServiceTest extends TestCase
{
    public function testCreateSubscriptionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $subscription = $client->subscriptions->create([
            'customer' => 'cus_test_123',
            'plan' => 'plan_test_123',
            'payment_method' => 'pm_test_123',
        ]);

        $this->assertSame('sub_test_123', $subscription->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions', $fake->lastRequest['url']);
        $this->assertSame('cus_test_123', $fake->lastRequest['params']['customer']);
    }

    public function testRetrieveSubscriptionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $subscription = $client->subscriptions->retrieve('sub_test_123');

        $this->assertSame('sub_test_123', $subscription->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions/sub_test_123', $fake->lastRequest['url']);
    }

    public function testListSubscriptionsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'sub_test_123',
                    'attributes' => $this->subscriptionAttributes('active'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $subscriptions = $client->subscriptions->all([
            'limit' => 10,
        ]);

        $this->assertCount(1, $subscriptions->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }

    public function testCancelSubscriptionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'canceled'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $subscription = $client->subscriptions->cancel('sub_test_123');

        $this->assertSame('canceled', $subscription->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions/sub_test_123/cancel', $fake->lastRequest['url']);
    }

    public function testUpdateSubscriptionPlanBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->subscriptions->updatePlan('sub_test_123', [
            'plan' => 'plan_test_new',
        ]);

        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions/sub_test_123/plan', $fake->lastRequest['url']);
        $this->assertSame('plan_test_new', $fake->lastRequest['params']['plan']);
    }

    public function testUpdateSubscriptionPaymentMethodBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->subscriptions->updatePaymentMethod('sub_test_123', [
            'payment_method' => 'pm_test_new',
        ]);

        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions/sub_test_123/payment_method', $fake->lastRequest['url']);
        $this->assertSame('pm_test_new', $fake->lastRequest['params']['payment_method']);
    }

    public function testTriggerSubscriptionCycleBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->subscriptionResource('sub_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->subscriptions->triggerCycle('sub_test_123', [
            'prorate' => true,
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/subscriptions/sub_test_123/trigger', $fake->lastRequest['url']);
        $this->assertTrue($fake->lastRequest['params']['prorate']);
    }

    /**
     * @return array<string, mixed>
     */
    private function subscriptionAttributes(string $status): array
    {
        return [
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
            'status' => $status,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function subscriptionResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->subscriptionAttributes($status),
            ],
        ]);
    }
}
