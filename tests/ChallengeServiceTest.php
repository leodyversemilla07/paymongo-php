<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ChallengeServiceTest extends TestCase
{
    public function testVerifyChallengeBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'ch_test_123',
                'attributes' => [
                    'status' => 'verified',
                    'type' => '3ds',
                    'livemode' => false,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->challenges->verify('ch_test_123', [
            'result' => 'success'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/challenges/ch_test_123/verify', $fake->lastRequest['url']);
        $this->assertSame('success', $fake->lastRequest['params']['result']);
    }
}
