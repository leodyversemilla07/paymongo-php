<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PlanServiceTest extends TestCase
{
    public function testCreatePlanBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->planResource('plan_test_123', 'Plan'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $plan = $client->plans->create([
            'amount' => 1000,
            'currency' => 'PHP',
            'interval' => 'month',
            'interval_count' => 1,
            'name' => 'Plan',
        ]);

        $this->assertSame('plan_test_123', $plan->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans', $fake->lastRequest['url']);
        $this->assertSame('Plan', $fake->lastRequest['params']['name']);
    }

    public function testRetrievePlanBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->planResource('plan_test_123', 'Plan'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $plan = $client->plans->retrieve('plan_test_123');

        $this->assertSame('plan_test_123', $plan->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans/plan_test_123', $fake->lastRequest['url']);
    }

    public function testUpdatePlanBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->planResource('plan_test_123', 'Plan Updated'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $plan = $client->plans->update('plan_test_123', [
            'name' => 'Plan Updated',
        ]);

        $this->assertSame('Plan Updated', $plan->name);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans/plan_test_123', $fake->lastRequest['url']);
        $this->assertSame('Plan Updated', $fake->lastRequest['params']['name']);
    }

    public function testListPlansBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'plan_test_123',
                    'attributes' => $this->planAttributes('Plan'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $plans = $client->plans->all([
            'limit' => 10,
        ]);

        $this->assertCount(1, $plans->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }

    /**
     * @return array<string, mixed>
     */
    private function planAttributes(string $name): array
    {
        return [
            'amount' => 1000,
            'currency' => 'PHP',
            'interval' => 'month',
            'interval_count' => 1,
            'livemode' => false,
            'name' => $name,
            'billing_statement_descriptor' => null,
            'description' => null,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function planResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->planAttributes($name),
            ],
        ]);
    }
}
