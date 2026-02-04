<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore6Test extends TestCase
{
    public function testLedgerEntryListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'le_test_123',
                    'attributes' => [
                        'account_id' => 'la_test_123',
                        'amount' => 1000,
                        'currency' => 'PHP',
                        'entry_type' => 'credit',
                        'livemode' => false,
                        'reference_number' => null,
                        'metadata' => null,
                        'created_at' => 0,
                        'updated_at' => 0,
                    ],
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $listing = $client->ledgerEntries->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('le_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_entries', $fake->lastRequest['url']);
    }

    public function testLedgerAccountCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'la_test_123',
                'attributes' => [
                    'account_code' => '1000',
                    'currency' => 'PHP',
                    'description' => null,
                    'livemode' => false,
                    'name' => 'Cash',
                    'type' => 'asset',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $account = $client->ledgerAccounts->create([
            'name' => 'Cash',
            'currency' => 'PHP',
            'type' => 'asset',
        ]);

        $this->assertSame('la_test_123', $account->id);
        $this->assertSame('Cash', $account->name);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts', $fake->lastRequest['url']);
    }

    public function testTransfersListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'tr_test_123',
                    'attributes' => [
                        'amount' => 1000,
                        'currency' => 'PHP',
                        'description' => 'Transfer',
                        'livemode' => false,
                        'reference_number' => null,
                        'status' => 'pending',
                        'recipient' => null,
                        'metadata' => null,
                        'created_at' => 0,
                        'updated_at' => 0,
                    ],
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $listing = $client->transfers->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('tr_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers', $fake->lastRequest['url']);
    }

    public function testWalletAccountListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'wa_test_123',
                    'attributes' => [
                        'account_name' => 'Main',
                        'account_number' => '1234567890',
                        'account_type' => 'checking',
                        'bank_code' => 'TEST',
                        'bank_name' => 'Test Bank',
                        'currency' => 'PHP',
                        'livemode' => false,
                        'status' => 'active',
                        'created_at' => 0,
                        'updated_at' => 0,
                    ],
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $listing = $client->walletAccounts->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('wa_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_accounts', $fake->lastRequest['url']);
    }
}
