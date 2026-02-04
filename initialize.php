<?php

declare(strict_types=1);

/**
 * PayMongo PHP SDK Manual Initialization File
 * 
 * Use this file when not using Composer autoload.
 * For Composer users, use: require_once 'vendor/autoload.php';
 */

$srcDir = dirname(__FILE__) . '/src';

// Core Classes
require $srcDir . '/PaymongoClient.php';
require $srcDir . '/PayMongo.php';
require $srcDir . '/ApiResource.php';
require $srcDir . '/Error.php';
require $srcDir . '/SourceError.php';
require $srcDir . '/HttpClient.php';

// Exceptions (load first as they're dependencies)
require $srcDir . '/Exceptions/BaseException.php';
require $srcDir . '/Exceptions/AuthenticationException.php';
require $srcDir . '/Exceptions/InvalidRequestException.php';
require $srcDir . '/Exceptions/InvalidServiceException.php';
require $srcDir . '/Exceptions/RouteNotFoundException.php';
require $srcDir . '/Exceptions/ResourceNotFoundException.php';
require $srcDir . '/Exceptions/UnexpectedValueException.php';
require $srcDir . '/Exceptions/SignatureVerificationException.php';
require $srcDir . '/Exceptions/PublicKeyException.php';
require $srcDir . '/Exceptions/ApiException.php';

// Base Classes
require $srcDir . '/Entities/BaseEntity.php';
require $srcDir . '/Services/BaseService.php';
require $srcDir . '/Services/ServiceFactory.php';

// Entities
require $srcDir . '/Entities/BillingAddress.php';
require $srcDir . '/Entities/Billing.php';
require $srcDir . '/Entities/Card.php';
require $srcDir . '/Entities/Cardholder.php';
require $srcDir . '/Entities/CardProgram.php';
require $srcDir . '/Entities/Challenge.php';
require $srcDir . '/Entities/CheckoutSession.php';
require $srcDir . '/Entities/ChildMerchant.php';
require $srcDir . '/Entities/Consumer.php';
require $srcDir . '/Entities/Customer.php';
require $srcDir . '/Entities/Event.php';
require $srcDir . '/Entities/FileRecord.php';
require $srcDir . '/Entities/InstallmentPlan.php';
require $srcDir . '/Entities/Invoice.php';
require $srcDir . '/Entities/Ledger.php';
require $srcDir . '/Entities/LedgerAccount.php';
require $srcDir . '/Entities/LedgerBalance.php';
require $srcDir . '/Entities/LedgerEntry.php';
require $srcDir . '/Entities/LedgerTransaction.php';
require $srcDir . '/Entities/Link.php';
require $srcDir . '/Entities/Listing.php';
require $srcDir . '/Entities/MerchantCapability.php';
require $srcDir . '/Entities/Payment.php';
require $srcDir . '/Entities/PaymentIntent.php';
require $srcDir . '/Entities/PaymentMethod.php';
require $srcDir . '/Entities/Plan.php';
require $srcDir . '/Entities/Policy.php';
require $srcDir . '/Entities/PolicyEvaluation.php';
require $srcDir . '/Entities/Product.php';
require $srcDir . '/Entities/QrPh.php';
require $srcDir . '/Entities/QrTransfer.php';
require $srcDir . '/Entities/Quote.php';
require $srcDir . '/Entities/Rate.php';
require $srcDir . '/Entities/ReceivingInstitution.php';
require $srcDir . '/Entities/Refund.php';
require $srcDir . '/Entities/Requirement.php';
require $srcDir . '/Entities/Review.php';
require $srcDir . '/Entities/Rule.php';
require $srcDir . '/Entities/RuleAttribute.php';
require $srcDir . '/Entities/Score.php';
require $srcDir . '/Entities/Source.php';
require $srcDir . '/Entities/Subscription.php';
require $srcDir . '/Entities/Transfer.php';
require $srcDir . '/Entities/Trigger.php';
require $srcDir . '/Entities/Wallet.php';
require $srcDir . '/Entities/WalletAccount.php';
require $srcDir . '/Entities/WalletTransaction.php';
require $srcDir . '/Entities/Webhook.php';
require $srcDir . '/Entities/Workflow.php';
require $srcDir . '/Entities/WorkflowAuthToken.php';

// Services
require $srcDir . '/Services/CardholderService.php';
require $srcDir . '/Services/CardProgramService.php';
require $srcDir . '/Services/CardService.php';
require $srcDir . '/Services/ChallengeService.php';
require $srcDir . '/Services/CheckoutSessionService.php';
require $srcDir . '/Services/ChildMerchantService.php';
require $srcDir . '/Services/ConsumerService.php';
require $srcDir . '/Services/CustomerService.php';
require $srcDir . '/Services/CustomerV2Service.php';
require $srcDir . '/Services/FileRecordService.php';
require $srcDir . '/Services/InstallmentPlanService.php';
require $srcDir . '/Services/InvoiceService.php';
require $srcDir . '/Services/LedgerAccountService.php';
require $srcDir . '/Services/LedgerBalanceService.php';
require $srcDir . '/Services/LedgerEntryService.php';
require $srcDir . '/Services/LedgerService.php';
require $srcDir . '/Services/LedgerTransactionService.php';
require $srcDir . '/Services/LegacyTransferService.php';
require $srcDir . '/Services/LegacyWalletTransactionService.php';
require $srcDir . '/Services/LinkService.php';
require $srcDir . '/Services/MerchantCapabilityService.php';
require $srcDir . '/Services/PaymentIntentService.php';
require $srcDir . '/Services/PaymentMethodService.php';
require $srcDir . '/Services/PaymentService.php';
require $srcDir . '/Services/PlanService.php';
require $srcDir . '/Services/PolicyService.php';
require $srcDir . '/Services/ProductService.php';
require $srcDir . '/Services/QrPhService.php';
require $srcDir . '/Services/QrTransferService.php';
require $srcDir . '/Services/QuoteService.php';
require $srcDir . '/Services/RateService.php';
require $srcDir . '/Services/RefundService.php';
require $srcDir . '/Services/RequirementService.php';
require $srcDir . '/Services/ReviewService.php';
require $srcDir . '/Services/RuleAttributeService.php';
require $srcDir . '/Services/RuleService.php';
require $srcDir . '/Services/ScoreService.php';
require $srcDir . '/Services/SourceService.php';
require $srcDir . '/Services/SubscriptionService.php';
require $srcDir . '/Services/TransferService.php';
require $srcDir . '/Services/TriggerService.php';
require $srcDir . '/Services/WalletAccountService.php';
require $srcDir . '/Services/WalletAccountV2Service.php';
require $srcDir . '/Services/WalletService.php';
require $srcDir . '/Services/WalletV2Service.php';
require $srcDir . '/Services/WebhookService.php';
require $srcDir . '/Services/WorkflowAuthService.php';
require $srcDir . '/Services/WorkflowService.php';
