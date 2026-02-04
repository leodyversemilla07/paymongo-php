<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationTest extends TestCase
{
    public function testPaymentServiceCreateHydratesEntity(): void
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
                    'description' => 'Test payment',
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
                    'tax_amount' => null,
                    'payment_intent_id' => null,
                    'refunds' => [],
                    'taxes' => [],
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $payment = $client->payments->create([
            'amount' => 1000,
            'currency' => 'PHP',
        ]);

        $this->assertSame('pay_test_123', $payment->id);
        $this->assertSame(1000, $payment->amount);
        $this->assertSame('PHP', $payment->currency);
        $this->assertSame('Test payment', $payment->description);
        $this->assertNull($payment->billing);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payments', $fake->lastRequest['url']);
    }
}
