<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class RuleServiceTest extends TestCase
{
    public function testCreateRuleBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'rule_test_123',
                'attributes' => [
                    'action' => 'block',
                    'conditions' => [],
                    'description' => 'Test rule',
                    'livemode' => false,
                    'name' => 'Rule',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->rules->create([
            'name' => 'Rule',
            'action' => 'block'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules', $fake->lastRequest['url']);
        $this->assertSame('Rule', $fake->lastRequest['params']['name']);
    }
}
