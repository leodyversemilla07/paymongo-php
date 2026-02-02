<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class HttpClientIdempotencyTest extends TestCase
{
    public function testHttpClientUsesPerRequestIdempotencyKey(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'pay_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'billing' => null,
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'fee' => 0,
                    'livemode' => false,
                    'net_amount' => 1000,
                    'statement_descriptor' => null,
                    'status' => 'paid',
                    'available_at' => null,
                    'created_at' => 0,
                    'paid_at' => null,
                    'payout' => null,
                    'updated_at' => 0,
                    'metadata' => null,
                    'source' => null,
                    'tax_amount' => 0,
                    'payment_intent_id' => null,
                    'refunds' => [],
                    'taxes' => []
                ]
            ]
        ]));

        $client->payments->create([
            'amount' => 1000,
            'currency' => 'PHP'
        ], [
            'idempotency_key' => 'idem_123'
        ]);

        $this->assertSame('idem_123', $fake->lastRequest['idempotency_key']);
    }
}
