<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class TriggerServiceTest extends TestCase
{
    public function testCreateTriggerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->triggerResource('trg_test_123', 'Trigger'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->triggers->create([
            'name' => 'Trigger',
            'action' => 'block',
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers', $fake->lastRequest['url']);
        $this->assertSame('Trigger', $fake->lastRequest['params']['name']);
    }

    public function testRetrieveTriggerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->triggerResource('trg_test_123', 'Trigger'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $trigger = $client->triggers->retrieve('trg_test_123');

        $this->assertSame('trg_test_123', $trigger->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers/trg_test_123', $fake->lastRequest['url']);
    }

    public function testListTriggersBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'trg_test_123',
                    'attributes' => $this->triggerAttributes('Trigger'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $listing = $client->triggers->all([
            'limit' => 5,
        ]);

        $this->assertCount(1, $listing->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers', $fake->lastRequest['url']);
        $this->assertSame(5, $fake->lastRequest['params']['limit']);
    }

    public function testUpdateTriggerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->triggerResource('trg_test_123', 'Trigger Updated'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $trigger = $client->triggers->update('trg_test_123', [
            'name' => 'Trigger Updated',
        ]);

        $this->assertSame('Trigger Updated', $trigger->name);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers/trg_test_123', $fake->lastRequest['url']);
        $this->assertSame('Trigger Updated', $fake->lastRequest['params']['name']);
    }

    public function testDeleteTriggerBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->triggerResource('trg_test_123', 'deleted'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $trigger = $client->triggers->delete('trg_test_123');

        $this->assertSame('trg_test_123', $trigger->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/triggers/trg_test_123', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
    private function triggerAttributes(string $name): array
    {
        return [
            'action' => 'block',
            'conditions' => [],
            'description' => 'Test trigger',
            'livemode' => false,
            'name' => $name,
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function triggerResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->triggerAttributes($name),
            ],
        ]);
    }
}
