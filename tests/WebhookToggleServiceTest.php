<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WebhookToggleServiceTest extends TestCase
{
    public function testEnableWebhookBuildsRequest(): void
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

        $client->webhooks->enable('wh_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123/enable', $fake->lastRequest['url']);
    }

    public function testDisableWebhookBuildsRequest(): void
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
                    'status' => 'disabled',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->webhooks->disable('wh_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123/disable', $fake->lastRequest['url']);
    }
}
