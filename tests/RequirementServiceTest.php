<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class RequirementServiceTest extends TestCase
{
    public function testRetrieveChildMerchantRequirementsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'req_test_123',
                'attributes' => [
                    'requirements' => [],
                    'livemode' => false,
                    'status' => 'pending',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->requirements->retrieveChildMerchant('cm_test_123');

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/child_merchants/cm_test_123/requirements', $fake->lastRequest['url']);
    }
}
