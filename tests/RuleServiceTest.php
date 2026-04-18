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
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ruleResource('rule_test_123', 'Rule'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $rule = $client->rules->create(['name' => 'Rule', 'action' => 'block']);

        $this->assertSame('rule_test_123', $rule->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules', $fake->lastRequest['url']);
    }

    public function testRetrieveRuleBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ruleResource('rule_test_123', 'Rule'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $rule = $client->rules->retrieve('rule_test_123');

        $this->assertSame('rule_test_123', $rule->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules/rule_test_123', $fake->lastRequest['url']);
    }

    public function testListRulesBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'rule_test_123',
                'attributes' => $this->ruleAttributes('Rule'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $rules = $client->rules->all(['limit' => 10]);

        $this->assertCount(1, $rules->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules', $fake->lastRequest['url']);
    }

    public function testUpdateRuleBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ruleResource('rule_test_123', 'Rule Updated'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $rule = $client->rules->update('rule_test_123', ['name' => 'Rule Updated']);

        $this->assertSame('Rule Updated', $rule->name);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules/rule_test_123', $fake->lastRequest['url']);
    }

    public function testDeleteRuleBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->ruleResource('rule_test_123', 'Rule'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $rule = $client->rules->delete('rule_test_123');

        $this->assertSame('rule_test_123', $rule->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rules/rule_test_123', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function ruleAttributes(string $name): array
    {
        return [
            'action' => 'block',
            'conditions' => [],
            'description' => 'Test rule',
            'livemode' => false,
            'name' => $name,
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function ruleResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->ruleAttributes($name),
            ],
        ]);
    }
}
