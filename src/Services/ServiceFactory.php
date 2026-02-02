<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Exceptions\InvalidServiceException;

/**
 * Factory class for creating service instances.
 */
class ServiceFactory
{
    /** @var array<string, class-string<BaseService>> Service name to class mappings */
    private array $classes = [
        'links' => LinkService::class,
        'customers' => CustomerService::class,
        'payments' => PaymentService::class,
        'paymentIntents' => PaymentIntentService::class,
        'paymentMethods' => PaymentMethodService::class,
        'refunds' => RefundService::class,
        'sources' => SourceService::class,
        'webhooks' => WebhookService::class,
        'checkoutSessions' => CheckoutSessionService::class,
        'customersV2' => CustomerV2Service::class,
        'subscriptions' => SubscriptionService::class,
        'plans' => PlanService::class,
        'invoices' => InvoiceService::class,
        'reviews' => ReviewService::class,
        'rules' => RuleService::class,
        'scores' => ScoreService::class,
        'walletsV2' => WalletV2Service::class,
        'transfers' => TransferService::class,
        'ledgers' => LedgerService::class,
        'rates' => RateService::class,
        'cards' => CardService::class,
        'childMerchants' => ChildMerchantService::class,
        'workflows' => WorkflowService::class,
        'policies' => PolicyService::class,
        'products' => ProductService::class,
        'legacyTransfers' => LegacyTransferService::class,
        'legacyWalletTransactions' => LegacyWalletTransactionService::class,
        'cardPrograms' => CardProgramService::class,
        'cardholders' => CardholderService::class,
        'challenges' => ChallengeService::class,
        'consumers' => ConsumerService::class,
        'fileRecords' => FileRecordService::class,
        'quotes' => QuoteService::class,
        'ledgerAccounts' => LedgerAccountService::class,
        'ledgerBalances' => LedgerBalanceService::class,
        'ledgerEntries' => LedgerEntryService::class,
        'ledgerTransactions' => LedgerTransactionService::class,
        'merchantCapabilities' => MerchantCapabilityService::class,
        'qrPh' => QrPhService::class,
        'qrTransfers' => QrTransferService::class,
        'requirements' => RequirementService::class,
        'ruleAttributes' => RuleAttributeService::class,
        'triggers' => TriggerService::class,
        'walletAccounts' => WalletAccountService::class,
        'walletAccountsV2' => WalletAccountV2Service::class,
        'wallets' => WalletService::class,
        'workflowAuth' => WorkflowAuthService::class,
        'installmentPlans' => InstallmentPlanService::class,
    ];

    /**
     * Get a service class by name.
     *
     * @param string $name The service name
     * @return class-string<BaseService> The service class
     * @throws InvalidServiceException If the service does not exist
     */
    public function get(string $name): string
    {
        if (array_key_exists($name, $this->classes)) {
            return $this->classes[$name];
        }

        throw new InvalidServiceException("Service {$name} does not exist.");
    }

    /**
     * Check if a service exists.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->classes);
    }

    /**
     * Get all available service names.
     *
     * @return array<string>
     */
    public function getAvailableServices(): array
    {
        return array_keys($this->classes);
    }
}
