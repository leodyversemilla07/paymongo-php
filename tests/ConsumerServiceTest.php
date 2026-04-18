<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ConsumerServiceTest extends TestCase
{
    public function testCreateConsumerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->consumerResource('cons_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $consumer = $client->consumers->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->assertSame('cons_test_123', $consumer->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers', $fake->lastRequest['url']);
    }

    public function testUpdateConsumerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->consumerResource('cons_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->consumers->update('cons_test_123', [
            'first_name' => 'Updated',
        ]);

        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123', $fake->lastRequest['url']);
        $this->assertSame('Updated', $fake->lastRequest['params']['first_name']);
    }

    public function testSubmitConsumerBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->consumerResource('cons_test_123', 'submitted'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $consumer = $client->consumers->submit('cons_test_123', ['remarks' => 'ready']);

        $this->assertSame('submitted', $consumer->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123/submit', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function consumerAttributes(string $status): array
    {
        return [
            'billing' => null,
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'livemode' => false,
            'phone' => '1234567890',
            'status' => $status,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function consumerResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->consumerAttributes($status),
            ],
        ]);
    }
}
