<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore9Test extends TestCase
{
    public function testWebhookRetrieveEnableDisableHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->webhookResource('wh_test_123', 'enabled'));
        $fake->queueResponse($this->webhookResource('wh_test_123', 'enabled'));
        $fake->queueResponse($this->webhookResource('wh_test_123', 'disabled'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $webhook = $client->webhooks->retrieve('wh_test_123');
        $this->assertSame('wh_test_123', $webhook->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123', $fake->lastRequest['url']);

        $enabled = $client->webhooks->enable('wh_test_123');
        $this->assertSame('enabled', $enabled->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123/enable', $fake->lastRequest['url']);

        $disabled = $client->webhooks->disable('wh_test_123');
        $this->assertSame('disabled', $disabled->status);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123/disable', $fake->lastRequest['url']);
    }

    public function testWorkflowRetrieveDeleteHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->workflowResource('wf_test_123'));
        $fake->queueResponse($this->workflowResource('wf_test_123'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $workflow = $client->workflows->retrieve('wf_test_123');
        $this->assertSame('wf_test_123', $workflow->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows/wf_test_123', $fake->lastRequest['url']);

        $deleted = $client->workflows->delete('wf_test_123');
        $this->assertSame('wf_test_123', $deleted->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows/wf_test_123', $fake->lastRequest['url']);
    }

    public function testPolicyRetrieveUpdateHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->policyResource('policy_test_123', 'Policy'));
        $fake->queueResponse($this->policyResource('policy_test_123', 'Policy Updated'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $policy = $client->policies->retrieve('policy_test_123');
        $this->assertSame('policy_test_123', $policy->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/policies/policy_test_123', $fake->lastRequest['url']);

        $updated = $client->policies->update('policy_test_123', [
            'name' => 'Policy Updated',
        ]);
        $this->assertSame('Policy Updated', $updated->name);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/policies/policy_test_123', $fake->lastRequest['url']);
    }

    public function testLedgerAccountRetrieveListUpdateDeleteHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->ledgerAccountResource('la_test_123', 'Cash'));
        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'la_test_123',
                    'attributes' => $this->ledgerAccountAttributes('Cash'),
                ],
            ],
            'has_more' => false,
        ]));
        $fake->queueResponse($this->ledgerAccountResource('la_test_123', 'Cash Updated'));
        $fake->queueResponse($this->ledgerAccountResource('la_test_123', 'Cash Updated'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $account = $client->ledgerAccounts->retrieve('la_test_123');
        $this->assertSame('la_test_123', $account->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts/la_test_123', $fake->lastRequest['url']);

        $listing = $client->ledgerAccounts->all();
        $this->assertCount(1, $listing->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts', $fake->lastRequest['url']);

        $updated = $client->ledgerAccounts->update('la_test_123', [
            'name' => 'Cash Updated',
        ]);
        $this->assertSame('Cash Updated', $updated->name);
        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts/la_test_123', $fake->lastRequest['url']);

        $deleted = $client->ledgerAccounts->delete('la_test_123');
        $this->assertSame('la_test_123', $deleted->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_accounts/la_test_123', $fake->lastRequest['url']);
    }

    public function testLedgerEntryRetrieveHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->ledgerEntryResource('le_test_123'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $entry = $client->ledgerEntries->retrieve('le_test_123');
        $this->assertSame('le_test_123', $entry->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_entries/le_test_123', $fake->lastRequest['url']);
    }

    public function testTransferRetrieveRetrieveBatchAndReceivingInstitutions(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse($this->transferResource('tr_test_123'));
        $fake->queueResponse($this->transferResource('tr_batch_123'));
        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'ri_test_123',
                    'attributes' => [
                        'bank_code' => 'TEST',
                        'name' => 'Test Bank',
                        'type' => 'bank',
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

        $transfer = $client->transfers->retrieve('tr_test_123');
        $this->assertSame('tr_test_123', $transfer->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers/tr_test_123', $fake->lastRequest['url']);

        $batch = $client->transfers->retrieveBatch('tr_batch_123');
        $this->assertSame('tr_batch_123', $batch->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers/tr_batch_123', $fake->lastRequest['url']);

        $institutions = $client->transfers->receivingInstitutions();
        $this->assertCount(1, $institutions->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/receiving_institutions', $fake->lastRequest['url']);
    }

    public function testWalletV2AndWalletAccountV2ListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wallet_test_123',
                'attributes' => [
                    'available_balance' => 5000,
                    'balance' => 7000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'name' => 'Main Wallet',
                    'type' => 'merchant',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

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

        $wallet = $client->walletsV2->retrieve('wallet_test_123');
        $this->assertSame('wallet_test_123', $wallet->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/wallets/wallet_test_123', $fake->lastRequest['url']);

        $accounts = $client->walletAccountsV2->all();
        $this->assertCount(1, $accounts->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v2/wallet_accounts', $fake->lastRequest['url']);
    }

    private function webhookResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.paid'],
                    'url' => 'https://example.com/webhook',
                    'status' => $status,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }

    private function workflowResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'description' => 'Test workflow',
                    'livemode' => false,
                    'name' => 'Workflow',
                    'status' => 'active',
                    'steps' => [],
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }

    private function policyResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => [
                    'description' => 'Test policy',
                    'livemode' => false,
                    'name' => $name,
                    'rules' => [],
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function ledgerAccountAttributes(string $name): array
    {
        return [
            'account_code' => '1000',
            'currency' => 'PHP',
            'description' => null,
            'livemode' => false,
            'name' => $name,
            'type' => 'asset',
            'status' => 'active',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function ledgerAccountResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->ledgerAccountAttributes($name),
            ],
        ]);
    }

    private function ledgerEntryResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
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
        ]);
    }

    private function transferResource(string $id): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
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
        ]);
    }
}
