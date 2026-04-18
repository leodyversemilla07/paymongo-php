<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CardholderServiceTest extends TestCase
{
    public function testCreateCardholderBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardholderResource('chd_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $cardholder = $client->cardholders->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->assertSame('chd_test_123', $cardholder->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cardholders', $fake->lastRequest['url']);
    }

    public function testRetrieveCardholderBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardholderResource('chd_test_123', 'test@example.com'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $cardholder = $client->cardholders->retrieve('chd_test_123');

        $this->assertSame('chd_test_123', $cardholder->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cardholders/chd_test_123', $fake->lastRequest['url']);
    }

    public function testListCardholdersBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'chd_test_123',
                'attributes' => $this->cardholderAttributes('test@example.com'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->cardholders->all(['limit' => 5]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cardholders', $fake->lastRequest['url']);
        $this->assertSame(5, $fake->lastRequest['params']['limit']);
    }

    /** @return array<string, mixed> */
    private function cardholderAttributes(string $email): array
    {
        return [
            'billing' => null,
            'email' => $email,
            'first_name' => 'Test',
            'last_name' => 'User',
            'livemode' => false,
            'phone' => '1234567890',
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function cardholderResource(string $id, string $email): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->cardholderAttributes($email),
            ],
        ]);
    }
}
