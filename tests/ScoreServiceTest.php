<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ScoreServiceTest extends TestCase
{
    public function testCreateScoreBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->scoreResource('score_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $score = $client->scores->create(['entity_id' => 'pay_test_123', 'entity_type' => 'payment']);

        $this->assertSame('score_test_123', $score->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/scores', $fake->lastRequest['url']);
    }

    public function testRetrieveScoreBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->scoreResource('score_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $score = $client->scores->retrieve('score_test_123');

        $this->assertSame('score_test_123', $score->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/scores/score_test_123', $fake->lastRequest['url']);
    }

    public function testListScoresBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'score_test_123',
                'attributes' => $this->scoreAttributes(),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $scores = $client->scores->all(['limit' => 10]);

        $this->assertCount(1, $scores->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/scores', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function scoreAttributes(): array
    {
        return [
            'decision' => 'allow',
            'entity_id' => 'pay_test_123',
            'entity_type' => 'payment',
            'livemode' => false,
            'metadata' => null,
            'score' => 90,
            'status' => 'completed',
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function scoreResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->scoreAttributes(),
            ],
        ]);
    }
}
