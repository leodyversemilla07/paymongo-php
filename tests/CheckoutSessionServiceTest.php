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
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'cs_test_123',
                'attributes' => [
                    'billing' => null,
                    'cancel_url' => 'https://example.com/cancel',
                    'description' => 'Test',
                    'line_items' => [],
                    'livemode' => false,
                    'payment_method_types' => ['card'],
                    'reference_number' => 'ref_123',
                    'success_url' => 'https://example.com/success',
                    'status' => 'active',
                    'url' => 'https://paymongo.link',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->checkoutSessions->create([
            'cancel_url' => 'https://example.com/cancel',
            'success_url' => 'https://example.com/success',
            'line_items' => []
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/checkout_sessions', $fake->lastRequest['url']);
        $this->assertSame('https://example.com/cancel', $fake->lastRequest['params']['cancel_url']);
    }
}
