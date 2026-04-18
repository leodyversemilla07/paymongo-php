<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class InstallmentPlanServiceTest extends TestCase
{
    public function testListInstallmentPlansBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'iplan_test_123',
                    'attributes' => [
                        'type' => 'card_installment_plan',
                        'installments' => 3,
                        'min_amount' => 10000,
                        'max_amount' => 50000,
                        'interest_rate' => 1.5,
                        'issuer' => 'test_issuer',
                        'status' => 'active',
                        'livemode' => false,
                        'created_at' => 0,
                        'updated_at' => 0,
                    ],
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $plans = $client->installmentPlans->all([
            'limit' => 10,
        ]);

        $this->assertCount(1, $plans->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/card_installment_plans', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }
}
