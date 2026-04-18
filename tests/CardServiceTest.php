<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CardServiceTest extends TestCase
{
    public function testCreateCardBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardResource('card_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $card = $client->cards->create([
            'cardholder' => 'chd_test_123',
            'brand' => 'visa',
        ]);

        $this->assertSame('card_test_123', $card->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cards', $fake->lastRequest['url']);
    }

    public function testRetrieveCardBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardResource('card_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $card = $client->cards->retrieve('card_test_123');

        $this->assertSame('card_test_123', $card->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cards/card_test_123', $fake->lastRequest['url']);
    }

    public function testListCardsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'card_test_123',
                'attributes' => $this->cardAttributes('active'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->cards->all(['limit' => 5]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cards', $fake->lastRequest['url']);
    }

    public function testActivateCardBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardResource('card_test_123', 'active'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->cards->activate('card_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cards/card_test_123/activate', $fake->lastRequest['url']);
    }

    public function testUpdateCardBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardResource('card_test_123', 'inactive'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $card = $client->cards->update('card_test_123', ['status' => 'inactive']);

        $this->assertSame('inactive', $card->status);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/cards/card_test_123', $fake->lastRequest['url']);
        $this->assertSame('inactive', $fake->lastRequest['params']['status']);
    }

    /** @return array<string, mixed> */
    private function cardAttributes(string $status): array
    {
        return [
            'brand' => 'visa',
            'currency' => 'PHP',
            'last4' => '4242',
            'livemode' => false,
            'status' => $status,
            'cardholder' => null,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function cardResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->cardAttributes($status),
            ],
        ]);
    }
}
