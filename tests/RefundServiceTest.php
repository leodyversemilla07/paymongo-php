<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class RefundServiceTest extends TestCase
{
    public function testCreateRefundBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'ref_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'balance_transaction_id' => null,
                    'livemode' => false,
                    'payment_id' => 'pay_test_123',
                    'payout_id' => null,
                    'notes' => null,
                    'reason' => 'requested_by_customer',
                    'status' => 'pending',
                    'available_at' => null,
                    'refunded_at' => null,
                    'currency' => 'PHP',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->refunds->create([
            'payment_id' => 'pay_test_123',
            'amount' => 1000,
            'reason' => 'requested_by_customer'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/refunds', $fake->lastRequest['url']);
        $this->assertSame('pay_test_123', $fake->lastRequest['params']['payment_id']);
    }
}
