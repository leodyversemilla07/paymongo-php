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
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentIntentResource('pi_test_123', 'awaiting_payment_method'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $intent = $client->paymentIntents->create([
            'amount' => 10000,
            'currency' => 'PHP',
            'payment_method_allowed' => ['card'],
        ]);

        $this->assertSame('pi_test_123', $intent->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents', $fake->lastRequest['url']);
        $this->assertSame(10000, $fake->lastRequest['params']['amount']);
    }

    public function testRetrievePaymentIntentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentIntentResource('pi_test_123', 'processing'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $intent = $client->paymentIntents->retrieve('pi_test_123');

        $this->assertSame('pi_test_123', $intent->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_test_123', $fake->lastRequest['url']);
    }

    public function testAttachPaymentIntentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentIntentResource('pi_test_123', 'processing'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->paymentIntents->attach('pi_test_123', [
            'payment_method' => 'pm_test_123',
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_test_123/attach', $fake->lastRequest['url']);
        $this->assertSame('pm_test_123', $fake->lastRequest['params']['payment_method']);
    }

    public function testCapturePaymentIntentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentIntentResource('pi_test_123', 'succeeded'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $intent = $client->paymentIntents->capture('pi_test_123', [
            'amount' => 10000,
        ]);

        $this->assertSame('succeeded', $intent->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_test_123/capture', $fake->lastRequest['url']);
        $this->assertSame(10000, $fake->lastRequest['params']['amount']);
    }

    public function testCancelPaymentIntentBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->paymentIntentResource('pi_test_123', 'canceled'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $intent = $client->paymentIntents->cancel('pi_test_123');

        $this->assertSame('canceled', $intent->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_test_123/cancel', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
    private function paymentIntentAttributes(string $status): array
    {
        return [
            'amount' => 10000,
            'capture_type' => 'automatic',
            'client_key' => 'pi_client_key',
            'currency' => 'PHP',
            'description' => 'Test',
            'livemode' => false,
            'last_payment_error' => null,
            'statement_descriptor' => null,
            'status' => $status,
            'payment_method_allowed' => ['card'],
            'payments' => [],
            'next_action' => null,
            'payment_method_options' => null,
            'metadata' => null,
            'setup_future_usage' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function paymentIntentResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->paymentIntentAttributes($status),
            ],
        ]);
    }
}
