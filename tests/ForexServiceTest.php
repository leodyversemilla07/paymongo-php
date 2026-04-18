<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ForexServiceTest extends TestCase
{
    public function testCreateQuoteBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->quoteResource('qt_test_123'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $quote = $client->quotes->create([
            'amount' => 1000,
            'currency' => 'USD',
            'converted_currency' => 'PHP',
        ]);

        $this->assertSame('qt_test_123', $quote->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/quotes', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }

    public function testRetrieveQuoteBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->quoteResource('qt_test_123'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $quote = $client->quotes->retrieve('qt_test_123');

        $this->assertSame('qt_test_123', $quote->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/quotes/qt_test_123', $fake->lastRequest['url']);
    }

    public function testRetrieveRateBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->rateResource('rate_test_123', 56.2));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $rate = $client->rates->retrieve('rate_test_123');

        $this->assertSame('rate_test_123', $rate->id);
        $this->assertSame(56.2, $rate->rate);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rates/rate_test_123', $fake->lastRequest['url']);
    }

    public function testSearchRatesBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'rate_test_123',
                    'attributes' => $this->rateAttributes(56.2),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $rates = $client->rates->search([
            'base_currency' => 'USD',
            'quote_currency' => 'PHP',
        ]);

        $this->assertCount(1, $rates->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/rates', $fake->lastRequest['url']);
        $this->assertSame('USD', $fake->lastRequest['params']['base_currency']);
        $this->assertSame('PHP', $fake->lastRequest['params']['quote_currency']);
    }

    /**
     * @return array<string, mixed>
     */
    private function quoteAttributes(): array
    {
        return [
            'amount' => 1000,
            'currency' => 'USD',
            'converted_amount' => 56000,
            'converted_currency' => 'PHP',
            'provider' => 'test',
            'rate_id' => 'rate_test_123',
            'livemode' => false,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function quoteResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->quoteAttributes(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rateAttributes(float $rate): array
    {
        return [
            'currency_pair' => 'USD/PHP',
            'provider' => 'test',
            'rate' => $rate,
            'livemode' => false,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function rateResource(string $id, float $rate): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->rateAttributes($rate),
            ],
        ]);
    }
}
