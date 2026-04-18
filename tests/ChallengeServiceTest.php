<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ChallengeServiceTest extends TestCase
{
    public function testCreateChallengeBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->challengeResource('ch_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $challenge = $client->challenges->create([
            'type' => '3ds',
            'payment_intent_id' => 'pi_test_123',
        ]);

        $this->assertSame('ch_test_123', $challenge->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/challenges', $fake->lastRequest['url']);
    }

    public function testVerifyChallengeBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->challengeResource('ch_test_123', 'verified'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->challenges->verify('ch_test_123', ['result' => 'success']);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/challenges/ch_test_123/verify', $fake->lastRequest['url']);
        $this->assertSame('success', $fake->lastRequest['params']['result']);
    }

    /** @return array<string, mixed> */
    private function challengeAttributes(string $status): array
    {
        return [
            'status' => $status,
            'type' => '3ds',
            'livemode' => false,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function challengeResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->challengeAttributes($status),
            ],
        ]);
    }
}
