<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class RuleAttributeServiceTest extends TestCase
{
    public function testListRuleAttributesBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => []
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->ruleAttributes->all();

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rule_attributes', $fake->lastRequest['url']);
    }
}
