<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PaymentIntentServiceTest extends TestCase
{
    public function testCreatePaymentIntentBuildsRequest(): void
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

        $client->paymentIntents->create([
            'amount' => 10000,
            'currency' => 'PHP',
            'payment_method_allowed' => ['card']
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents', $fake->lastRequest['url']);
        $this->assertSame(10000, $fake->lastRequest['params']['amount']);
    }
}
