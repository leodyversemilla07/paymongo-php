<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceFactoryTest extends TestCase
{
    public function testServiceFactoryResolvesServices(): void
    {
        $client = new \Paymongo\PaymongoClient('sk_test_key');

        $this->assertInstanceOf(\Paymongo\Services\CheckoutSessionService::class, $client->checkoutSessions);
        $this->assertInstanceOf(\Paymongo\Services\CustomerV2Service::class, $client->customersV2);
        $this->assertInstanceOf(\Paymongo\Services\SubscriptionService::class, $client->subscriptions);
        $this->assertInstanceOf(\Paymongo\Services\PlanService::class, $client->plans);
        $this->assertInstanceOf(\Paymongo\Services\InvoiceService::class, $client->invoices);
        $this->assertInstanceOf(\Paymongo\Services\ReviewService::class, $client->reviews);
        $this->assertInstanceOf(\Paymongo\Services\RuleService::class, $client->rules);
        $this->assertInstanceOf(\Paymongo\Services\ScoreService::class, $client->scores);
        $this->assertInstanceOf(\Paymongo\Services\WalletV2Service::class, $client->walletsV2);
        $this->assertInstanceOf(\Paymongo\Services\TransferService::class, $client->transfers);
        $this->assertInstanceOf(\Paymongo\Services\LedgerService::class, $client->ledgers);
        $this->assertInstanceOf(\Paymongo\Services\RateService::class, $client->rates);
        $this->assertInstanceOf(\Paymongo\Services\CardService::class, $client->cards);
        $this->assertInstanceOf(\Paymongo\Services\ChildMerchantService::class, $client->childMerchants);
        $this->assertInstanceOf(\Paymongo\Services\WorkflowService::class, $client->workflows);
        $this->assertInstanceOf(\Paymongo\Services\PolicyService::class, $client->policies);
        $this->assertInstanceOf(\Paymongo\Services\ProductService::class, $client->products);
        $this->assertInstanceOf(\Paymongo\Services\LegacyTransferService::class, $client->legacyTransfers);
        $this->assertInstanceOf(\Paymongo\Services\LegacyWalletTransactionService::class, $client->legacyWalletTransactions);
    }
}
