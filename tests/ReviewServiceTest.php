<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ReviewServiceTest extends TestCase
{
    public function testApproveReviewBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'rvw_test_123',
                'attributes' => [
                    'decision' => 'approve',
                    'entity_id' => 'pay_test_123',
                    'entity_type' => 'payment',
                    'livemode' => false,
                    'metadata' => null,
                    'reason' => 'manual',
                    'status' => 'approved',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->reviews->approve('rvw_test_123');

        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/reviews/rvw_test_123/approve', $fake->lastRequest['url']);
    }
}
