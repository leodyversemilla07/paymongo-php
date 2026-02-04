<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMoreTest extends TestCase
{
    public function testPaymentIntentCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'pi_test_123',
                'attributes' => [
                    'amount' => 10000,
                    'capture_type' => 'automatic',
                    'client_key' => 'pi_client_key',
                    'currency' => 'PHP',
                    'description' => 'Test',
                    'livemode' => false,
                    'last_payment_error' => null,
                    'statement_descriptor' => null,
                    'status' => 'awaiting_payment_method',
                    'payment_method_allowed' => ['card'],
                    'payments' => [],
                    'next_action' => null,
                    'payment_method_options' => null,
                    'metadata' => null,
                    'setup_future_usage' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $intent = $client->paymentIntents->create([
            'amount' => 10000,
            'currency' => 'PHP',
            'payment_method_allowed' => ['card']
        ]);

        $this->assertSame('pi_test_123', $intent->id);
        $this->assertSame(10000, $intent->amount);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents', $fake->lastRequest['url']);
    }

    public function testRefundCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 're_test_123',
                'attributes' => [
                    'amount' => 500,
                    'balance_transaction_id' => null,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'metadata' => null,
                    'payment_id' => 'pay_test_123',
                    'reason' => 'requested_by_customer',
                    'status' => 'pending',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $refund = $client->refunds->create([
            'amount' => 500,
            'payment_id' => 'pay_test_123'
        ]);

        $this->assertSame('re_test_123', $refund->id);
        $this->assertSame(500, $refund->amount);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/refunds', $fake->lastRequest['url']);
    }

    public function testCheckoutSessionCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'cs_test_123',
                'attributes' => [
                    'billing' => null,
                    'cancel_url' => 'https://example.com/cancel',
                    'description' => 'Order #123',
                    'line_items' => [],
                    'livemode' => false,
                    'payment_method_types' => ['card'],
                    'reference_number' => null,
                    'success_url' => 'https://example.com/success',
                    'status' => 'active',
                    'url' => 'https://checkout.paymongo.com/cs_test_123',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $session = $client->checkoutSessions->create([
            'line_items' => [],
            'payment_method_types' => ['card'],
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);

        $this->assertSame('cs_test_123', $session->id);
        $this->assertSame('https://checkout.paymongo.com/cs_test_123', $session->url);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/checkout_sessions', $fake->lastRequest['url']);
    }
}
