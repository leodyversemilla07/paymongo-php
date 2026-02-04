# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.1] - 2026-02-04
### Added
- Expanded test coverage for service integrations, required-field validation, and webhook edge cases
- Additional integration tests for ledger, wallet, and transfer flows

## [1.0.0] - 2026-02-02

### Added
- `PaymongoClient::VERSION` constant for programmatic version access
- Comprehensive API coverage for all PayMongo resources:
  - **Core Payments**: PaymentIntents, PaymentMethods, Payments, Refunds, Customers (v1 & v2)
  - **Checkout**: CheckoutSessions, QR Ph, Links
  - **Subscriptions**: Subscriptions, Plans, Invoices
  - **Fraud Detection**: Reviews, Rules, RuleAttributes, Scores
  - **Wallets & Transfers**: Wallets (v1 & v2), WalletAccounts (v1 & v2), Transfers, QrTransfers, Ledgers, LedgerAccounts, LedgerTransactions, LedgerEntries, LedgerBalances
  - **Forex**: Rates, Quotes
  - **Issuing**: CardPrograms, Cardholders, Cards, Challenges
  - **Onboarding**: ChildMerchants, Consumers, Requirements, FileRecords, MerchantCapabilities
  - **Workflows**: Workflows, WorkflowAuth, Triggers, Policies
- 50 service classes with consistent CRUD patterns
- 50 entity classes with `toArray()` serialization
- 10 specific exception types for granular error handling
- Webhook signature verification
- Idempotency key support
- Configurable HTTP timeouts
- Full test suite (54 tests, 155 assertions)

### Changed
- Marked as stable public API
