<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PaymentServiceTest extends TestCase
{
    public function testListPaymentsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'pay_test_123',
                    'attributes' => $this->paymentAttributes('paid'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $payments = $client->payments->all([
            'limit' => 10,
        ]);

        $this->assertCount(1, $payments->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payments', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }

    public function testRetrievePaymentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentResource('pay_test_123', 'paid'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $payment = $client->payments->retrieve('pay_test_123');

        $this->assertSame('pay_test_123', $payment->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payments/pay_test_123', $fake->lastRequest['url']);
    }

    public function testCapturePaymentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentResource('pay_test_123', 'paid'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->payments->capture('pay_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payments/pay_test_123/capture', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
    private function paymentAttributes(string $status): array
    {
        return [
            'amount' => 1000,
            'billing' => null,
            'currency' => 'PHP',
            'description' => 'Test',
            'fee' => 0,
            'livemode' => false,
            'net_amount' => 1000,
            'statement_descriptor' => null,
            'status' => $status,
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
            'taxes' => [],
        ];
    }

    private function paymentResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->paymentAttributes($status),
            ],
        ]);
    }
}
