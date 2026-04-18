<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LegacyWalletTransactionServiceTest extends TestCase
{
    public function testListLegacyWalletTransactionsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'wt_test_123',
                'attributes' => $this->walletTxAttributes(),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->legacyWalletTransactions->all(['limit' => 10]);

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_transactions', $fake->lastRequest['url']);
    }

    public function testCreateLegacyWalletTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->walletTxResource('wt_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->legacyWalletTransactions->create(['amount' => 1000, 'currency' => 'PHP']);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_transactions', $fake->lastRequest['url']);
    }

    public function testRetrieveLegacyWalletTransactionBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->walletTxResource('wt_test_123'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $tx = $client->legacyWalletTransactions->retrieve('wt_test_123');

        $this->assertSame('wt_test_123', $tx->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_transactions/wt_test_123', $fake->lastRequest['url']);
    }

    public function testLegacyWalletReceivingInstitutionsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'ri_test_123',
                'attributes' => [
                    'bank_code' => 'TEST',
                    'name' => 'Test Bank',
                    'type' => 'bank',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $list = $client->legacyWalletTransactions->receivingInstitutions();

        $this->assertCount(1, $list->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/receiving_institutions', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function walletTxAttributes(): array
    {
        return [
            'amount' => 1000,
            'currency' => 'PHP',
            'description' => 'Test',
            'livemode' => false,
            'reference_number' => 'ref_123',
            'status' => 'pending',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function walletTxResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->walletTxAttributes(),
            ],
        ]);
    }
}
