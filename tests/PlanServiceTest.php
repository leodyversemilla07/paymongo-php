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
                    'name' => 'Plan',
                    'billing_statement_descriptor' => null,
                    'description' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->plans->create([
            'amount' => 1000,
            'currency' => 'PHP',
            'interval' => 'month',
            'interval_count' => 1,
            'name' => 'Plan'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/plans', $fake->lastRequest['url']);
        $this->assertSame('Plan', $fake->lastRequest['params']['name']);
    }
}
