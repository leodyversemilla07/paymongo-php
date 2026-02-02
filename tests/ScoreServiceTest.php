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
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'score_test_123',
                'attributes' => [
                    'decision' => 'allow',
                    'entity_id' => 'pay_test_123',
                    'entity_type' => 'payment',
                    'livemode' => false,
                    'metadata' => null,
                    'score' => 90,
                    'status' => 'completed',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->scores->create([
            'entity_id' => 'pay_test_123',
            'entity_type' => 'payment'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/scores', $fake->lastRequest['url']);
        $this->assertSame('pay_test_123', $fake->lastRequest['params']['entity_id']);
    }
}
