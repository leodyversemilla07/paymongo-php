<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WebhookServiceTest extends TestCase
{
    public function testCreateWebhookBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'wh_test_123',
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.paid'],
                    'url' => 'https://example.com/webhook',
                    'status' => 'enabled',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->webhooks->create([
            'url' => 'https://example.com/webhook',
            'events' => ['payment.paid']
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks', $fake->lastRequest['url']);
        $this->assertSame('https://example.com/webhook', $fake->lastRequest['params']['url']);
    }
}
