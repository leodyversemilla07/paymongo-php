<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CheckoutSessionServiceTest extends TestCase
{
    public function testCreateCheckoutSessionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->checkoutSessionResource('cs_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $session = $client->checkoutSessions->create([
            'cancel_url' => 'https://example.com/cancel',
            'success_url' => 'https://example.com/success',
            'line_items' => [],
        ]);

        $this->assertSame('cs_test_123', $session->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/checkout_sessions', $fake->lastRequest['url']);
        $this->assertSame('https://example.com/cancel', $fake->lastRequest['params']['cancel_url']);
    }

    public function testRetrieveCheckoutSessionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->checkoutSessionResource('cs_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $session = $client->checkoutSessions->retrieve('cs_test_123');

        $this->assertSame('cs_test_123', $session->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/checkout_sessions/cs_test_123', $fake->lastRequest['url']);
    }

    public function testExpireCheckoutSessionBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->checkoutSessionResource('cs_test_123', 'expired'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $session = $client->checkoutSessions->expire('cs_test_123');

        $this->assertSame('expired', $session->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/checkout_sessions/cs_test_123/expire', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
    private function checkoutSessionAttributes(string $status): array
    {
        return [
            'billing' => null,
            'cancel_url' => 'https://example.com/cancel',
            'description' => 'Test',
            'line_items' => [],
            'livemode' => false,
            'payment_method_types' => ['card'],
            'reference_number' => 'ref_123',
            'success_url' => 'https://example.com/success',
            'status' => $status,
            'url' => 'https://paymongo.link',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function checkoutSessionResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->checkoutSessionAttributes($status),
            ],
        ]);
    }
}
