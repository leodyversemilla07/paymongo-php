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
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->requirementResource('req_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->requirements->retrieveChildMerchant('cm_test_123');

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/requirements', $fake->lastRequest['url']);
    }

    public function testRetrieveConsumerRequirementsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->requirementResource('req_test_124'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $requirement = $client->requirements->retrieveConsumer('cons_test_123');

        $this->assertSame('req_test_124', $requirement->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123/requirements', $fake->lastRequest['url']);
    }

    private function requirementResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'requirements' => [],
                    'livemode' => false,
                    'status' => 'pending',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }
}
