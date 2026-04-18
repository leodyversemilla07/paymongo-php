<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class CardProgramServiceTest extends TestCase
{
    public function testCreateCardProgramBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardProgramResource('cp_test_123', 'Program'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $program = $client->cardPrograms->create([
            'name' => 'Program',
            'type' => 'virtual',
            'currency' => 'PHP',
        ]);

        $this->assertSame('cp_test_123', $program->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/card_programs', $fake->lastRequest['url']);
    }

    public function testRetrieveCardProgramBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->cardProgramResource('cp_test_123', 'Program'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $program = $client->cardPrograms->retrieve('cp_test_123');

        $this->assertSame('cp_test_123', $program->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/card_programs/cp_test_123', $fake->lastRequest['url']);
    }

    public function testListCardProgramsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'cp_test_123',
                'attributes' => $this->cardProgramAttributes('Program'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->cardPrograms->all(['limit' => 5]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/card_programs', $fake->lastRequest['url']);
        $this->assertSame(5, $fake->lastRequest['params']['limit']);
    }

    /** @return array<string, mixed> */
    private function cardProgramAttributes(string $name): array
    {
        return [
            'name' => $name,
            'type' => 'virtual',
            'currency' => 'PHP',
            'livemode' => false,
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function cardProgramResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->cardProgramAttributes($name),
            ],
        ]);
    }
}
