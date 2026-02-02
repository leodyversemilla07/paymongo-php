<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class TriggerServiceTest extends TestCase
{
    public function testCreateTriggerBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'trg_test_123',
                'attributes' => [
                    'action' => 'block',
                    'conditions' => [],
                    'description' => 'Test trigger',
                    'livemode' => false,
                    'name' => 'Trigger',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->triggers->create([
            'name' => 'Trigger',
            'action' => 'block'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers', $fake->lastRequest['url']);
        $this->assertSame('Trigger', $fake->lastRequest['params']['name']);
    }
}
