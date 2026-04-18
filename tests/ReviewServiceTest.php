<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ReviewServiceTest extends TestCase
{
    public function testRetrieveReviewBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->reviewResource('rvw_test_123', 'pending'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $review = $client->reviews->retrieve('rvw_test_123');

        $this->assertSame('rvw_test_123', $review->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/reviews/rvw_test_123', $fake->lastRequest['url']);
    }

    public function testListReviewsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'rvw_test_123',
                'attributes' => $this->reviewAttributes('pending'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $reviews = $client->reviews->all(['limit' => 10]);

        $this->assertCount(1, $reviews->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/reviews', $fake->lastRequest['url']);
    }

    public function testApproveReviewBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->reviewResource('rvw_test_123', 'approved'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $review = $client->reviews->approve('rvw_test_123');

        $this->assertSame('approved', $review->status);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/reviews/rvw_test_123/approve', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function reviewAttributes(string $status): array
    {
        return [
            'decision' => 'approve',
            'entity_id' => 'pay_test_123',
            'entity_type' => 'payment',
            'livemode' => false,
            'metadata' => null,
            'reason' => 'manual',
            'status' => $status,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function reviewResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->reviewAttributes($status),
            ],
        ]);
    }
}
