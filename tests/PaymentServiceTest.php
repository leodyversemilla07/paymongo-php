<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PaymentServiceTest extends TestCase
{
    public function testCapturePaymentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
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

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->payments->capture('pay_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payments/pay_test_123/capture', $fake->lastRequest['url']);
    }
}
