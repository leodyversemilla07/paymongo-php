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
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->refundResource('ref_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->refunds->create([
            'payment_id' => 'pay_test_123',
            'amount' => 1000,
            'reason' => 'requested_by_customer',
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/refunds', $fake->lastRequest['url']);
    }

    public function testRetrieveRefundBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->refundResource('ref_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $refund = $client->refunds->retrieve('ref_test_123');

        $this->assertSame('ref_test_123', $refund->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/refunds/ref_test_123', $fake->lastRequest['url']);
    }

    private function refundResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'amount' => 1000,
                    'balance_transaction_id' => null,
                    'livemode' => false,
                    'payment_id' => 'pay_test_123',
                    'payout_id' => null,
                    'notes' => null,
                    'reason' => 'requested_by_customer',
                    'status' => $status,
                    'available_at' => null,
                    'refunded_at' => null,
                    'currency' => 'PHP',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }
}
