# PayMongo API Coverage Matrix

This document maps SDK services to official PayMongo API resources and the test files that validate request construction. This is an engineering checklist; see README for usage.

Core
- Payment Intents: create/retrieve/attach/capture/cancel
  - Service: src/Services/PaymentIntentService.php
  - Tests: tests/PaymentIntentServiceTest.php
- Payment Methods: create/retrieve/update
  - Service: src/Services/PaymentMethodService.php
  - Tests: tests/PaymentMethodServiceTest.php
- Payments: list/retrieve/capture/cancel/create
  - Service: src/Services/PaymentService.php
  - Tests: tests/PaymentServiceTest.php
- Refunds: list/retrieve/create
  - Service: src/Services/RefundService.php
  - Tests: tests/RefundServiceTest.php, tests/RefundListServiceTest.php
- Customers v1: create/retrieve/update/delete/list & delete payment methods
  - Service: src/Services/CustomerService.php
  - Tests: tests/CustomerPaymentMethodServiceTest.php
- Customers v2: create/list/retrieve/update
  - Service: src/Services/CustomerV2Service.php
  - Tests: tests/CustomerV2ServiceTest.php
- Webhooks: create/retrieve/list/update/enable/disable/construct event
  - Service: src/Services/WebhookService.php
  - Tests: tests/WebhookServiceTest.php, tests/WebhookToggleServiceTest.php, tests/WebhookSignatureTest.php

Checkout and Channels
- Checkout Sessions: create/retrieve/expire
  - Service: src/Services/CheckoutSessionService.php
  - Tests: tests/CheckoutSessionServiceTest.php
- QR Ph: create static code
  - Service: src/Services/QrPhService.php
  - Tests: tests/QrPhServiceTest.php

Subscriptions
- Subscriptions: create/retrieve/list/cancel/update plan/update payment method/trigger cycle
  - Service: src/Services/SubscriptionService.php
  - Tests: tests/SubscriptionServiceTest.php
- Plans: create/retrieve/update/list
  - Service: src/Services/PlanService.php
  - Tests: tests/PlanServiceTest.php
- Invoices: list/retrieve/create line items/pay
  - Service: src/Services/InvoiceService.php
  - Tests: tests/InvoiceServiceTest.php

Fraud Detection
- Reviews: retrieve/list/approve
  - Service: src/Services/ReviewService.php
  - Tests: tests/ReviewServiceTest.php
- Rules: create/retrieve/list/update/delete
  - Service: src/Services/RuleService.php
  - Tests: tests/RuleServiceTest.php
- Rule Attributes: list
  - Service: src/Services/RuleAttributeService.php
  - Tests: tests/RuleAttributeServiceTest.php
- Scores: create/retrieve/list
  - Service: src/Services/ScoreService.php
  - Tests: tests/ScoreServiceTest.php

Wallets, Transfers, Ledgers
- Wallets v1: retrieve
  - Service: src/Services/WalletService.php
  - Tests: tests/WalletServiceTest.php
- Wallet Accounts v1: list
  - Service: src/Services/WalletAccountService.php
  - Tests: tests/WalletAccountServiceTest.php
- Wallets v2: retrieve
  - Service: src/Services/WalletV2Service.php
  - Tests: tests/WalletV2ServiceTest.php
- Wallet Accounts v2: list
  - Service: src/Services/WalletAccountV2Service.php
  - Tests: tests/WalletAccountV2ServiceTest.php
- Transfers v2: create batch/retrieve batch/retrieve/list/receiving institutions
  - Service: src/Services/TransferService.php
  - Tests: tests/TransferServiceTest.php
- QR Transfers: generate/execute
  - Service: src/Services/QrTransferService.php
  - Tests: tests/QrTransferServiceTest.php
- Ledgers: create/retrieve/list/update/delete
  - Service: src/Services/LedgerService.php
  - Tests: tests/LedgerServiceTest.php
- Ledger Accounts: create/retrieve/list/update/delete
  - Service: src/Services/LedgerAccountService.php
  - Tests: tests/LedgerAccountServiceTest.php
- Ledger Transactions: create/retrieve/list/update/reverse
  - Service: src/Services/LedgerTransactionService.php
  - Tests: tests/LedgerTransactionServiceTest.php
- Ledger Entries: retrieve/list/update
  - Service: src/Services/LedgerEntryService.php
  - Tests: tests/LedgerEntryServiceTest.php
- Ledger Balances: list
  - Service: src/Services/LedgerBalanceService.php
  - Tests: tests/LedgerBalanceServiceTest.php

Forex
- Rates: retrieve/search
  - Service: src/Services/RateService.php
  - Tests: tests/ForexServiceTest.php
- Quotes: create/retrieve
  - Service: src/Services/QuoteService.php
  - Tests: tests/ForexServiceTest.php

Issuing
- Card Programs: create/retrieve/list
  - Service: src/Services/CardProgramService.php
  - Tests: tests/CardProgramServiceTest.php
- Cardholders: create/retrieve/list
  - Service: src/Services/CardholderService.php
  - Tests: tests/CardholderServiceTest.php
- Cards: create/retrieve/list/activate/update
  - Service: src/Services/CardService.php
  - Tests: tests/CardServiceTest.php
- Challenges: create/verify
  - Service: src/Services/ChallengeService.php
  - Tests: tests/ChallengeServiceTest.php

Onboarding
- Child Merchants: create/list/update/submit
  - Service: src/Services/ChildMerchantService.php
  - Tests: tests/OnboardingServiceTest.php
- Consumers: create/update/submit
  - Service: src/Services/ConsumerService.php
  - Tests: tests/ConsumerServiceTest.php
- Requirements: retrieve for child merchants/consumers
  - Service: src/Services/RequirementService.php
  - Tests: tests/RequirementServiceTest.php
- File Records: list/create/delete for child merchants and consumers
  - Service: src/Services/FileRecordService.php
  - Tests: tests/FileRecordServiceTest.php
- Merchant Capabilities: list
  - Service: src/Services/MerchantCapabilityService.php
  - Tests: tests/MerchantCapabilityServiceTest.php

Workflows and Policies
- Workflow Auth: issue token
  - Service: src/Services/WorkflowAuthService.php
  - Tests: tests/WorkflowAuthServiceTest.php
- Workflows: create/retrieve/list/delete/execute
  - Service: src/Services/WorkflowService.php
  - Tests: tests/WorkflowServiceTest.php
- Triggers: create/retrieve/list/update/delete
  - Service: src/Services/TriggerService.php
  - Tests: tests/TriggerServiceTest.php
- Policies: list/retrieve/create/update/evaluate
  - Service: src/Services/PolicyService.php
  - Tests: tests/PolicyServiceTest.php

Legacy (Deprecated)
- Transfers v1: create batch/retrieve batch/list
  - Service: src/Services/LegacyTransferService.php
  - Tests: tests/LegacyTransferServiceTest.php
- Wallet Transactions v1: list/create/retrieve/receiving institutions
  - Service: src/Services/LegacyWalletTransactionService.php
  - Tests: tests/LegacyWalletTransactionServiceTest.php
