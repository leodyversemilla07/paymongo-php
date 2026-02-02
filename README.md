# PayMongo PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leodyversemilla07/paymongo-php.svg?style=flat-square)](https://packagist.org/packages/leodyversemilla07/paymongo-php)
[![Tests](https://github.com/leodyversemilla07/paymongo-php/actions/workflows/ci.yml/badge.svg)](https://github.com/leodyversemilla07/paymongo-php/actions/workflows/ci.yml)
[![License](https://img.shields.io/github/license/leodyversemilla07/paymongo-php.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/leodyversemilla07/paymongo-php.svg?style=flat-square)](https://packagist.org/packages/leodyversemilla07/paymongo-php)

A comprehensive PHP SDK for integrating with the [PayMongo](https://paymongo.com) payment gateway API.

## Features

- Complete PayMongo API coverage
- Payment Intents, Payment Methods, Payments, Refunds
- Checkout Sessions, Links
- QR Ph payments
- Subscriptions, Plans, Invoices
- Fraud Detection (Reviews, Rules, Scores)
- Wallets, Transfers, Ledgers
- Forex (Rates, Quotes)
- Card Issuing (Programs, Cardholders, Cards)
- Merchant Onboarding
- Webhook signature verification
- Idempotency support

## Requirements

- PHP 8.2 or higher
- cURL extension
- JSON extension
- mbstring extension

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require leodyversemilla07/paymongo-php
```

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use Paymongo\PaymongoClient;

$client = new PaymongoClient('sk_test_your_secret_key');

// Create a payment intent
$paymentIntent = $client->paymentIntents->create([
    'amount' => 10000, // Amount in cents (100.00 PHP)
    'currency' => 'PHP',
    'payment_method_allowed' => ['card', 'gcash', 'grab_pay'],
    'description' => 'Order #12345',
]);

echo $paymentIntent->id;
echo $paymentIntent->clientKey;
```

## Usage Examples

### Payment Intents

```php
// Create a payment intent
$paymentIntent = $client->paymentIntents->create([
    'amount' => 10000,
    'currency' => 'PHP',
    'payment_method_allowed' => ['card'],
]);

// Retrieve a payment intent
$paymentIntent = $client->paymentIntents->retrieve('pi_xxx');

// Capture a payment intent
$paymentIntent = $client->paymentIntents->capture('pi_xxx', [
    'amount' => 10000,
]);

// Cancel a payment intent
$paymentIntent = $client->paymentIntents->cancel('pi_xxx');
```

### Checkout Sessions

```php
$checkoutSession = $client->checkoutSessions->create([
    'description' => 'Order #12345',
    'line_items' => [
        [
            'name' => 'Product Name',
            'quantity' => 1,
            'amount' => 10000,
            'currency' => 'PHP',
        ],
    ],
    'payment_method_types' => ['card', 'gcash', 'grab_pay'],
    'success_url' => 'https://example.com/success',
    'cancel_url' => 'https://example.com/cancel',
]);

// Redirect user to checkout
header('Location: ' . $checkoutSession->checkoutUrl);
```

### Refunds

```php
// Create a refund
$refund = $client->refunds->create([
    'amount' => 5000,
    'payment_id' => 'pay_xxx',
    'reason' => 'requested_by_customer',
]);

// List refunds
$refunds = $client->refunds->all(['limit' => 10]);
```

### Webhooks

```php
// Create a webhook
$webhook = $client->webhooks->create([
    'url' => 'https://example.com/webhooks/paymongo',
    'events' => [
        'payment.paid',
        'payment.failed',
    ],
]);

// Verify webhook signature
try {
    $payload = file_get_contents('php://input');
    $signatureHeader = $_SERVER['HTTP_PAYMONGO_SIGNATURE'];
    
    $event = $client->webhooks->constructEvent([
        'payload' => $payload,
        'signature_header' => $signatureHeader,
        'webhook_secret_key' => 'whsec_xxx',
    ]);

    switch ($event->type) {
        case 'payment.paid':
            // Handle successful payment
            break;
        case 'payment.failed':
            // Handle failed payment
            break;
    }

    http_response_code(200);
} catch (\Paymongo\Exceptions\SignatureVerificationException $e) {
    http_response_code(400);
    exit('Invalid signature');
}
```

### Subscriptions

```php
// Create a plan
$plan = $client->plans->create([
    'name' => 'Premium Plan',
    'amount' => 99900,
    'currency' => 'PHP',
    'interval' => 'month',
]);

// Create a subscription
$subscription = $client->subscriptions->create([
    'customer_id' => 'cus_xxx',
    'plan_id' => 'plan_xxx',
    'payment_method_id' => 'pm_xxx',
]);

// Cancel a subscription
$subscription = $client->subscriptions->cancel('sub_xxx');
```

## Error Handling

```php
use Paymongo\Exceptions\InvalidRequestException;
use Paymongo\Exceptions\AuthenticationException;
use Paymongo\Exceptions\ResourceNotFoundException;

try {
    $paymentIntent = $client->paymentIntents->create([
        'amount' => 10000,
        'currency' => 'PHP',
        'payment_method_allowed' => ['card'],
    ]);
} catch (AuthenticationException $e) {
    // Invalid API key
    echo 'Authentication failed: ' . $e->getMessage();
} catch (InvalidRequestException $e) {
    // Invalid parameters
    foreach ($e->getError() as $error) {
        echo $error->detail;
    }
} catch (ResourceNotFoundException $e) {
    // Resource not found
    echo 'Resource not found';
}
```

## Configuration

```php
$client = new PaymongoClient('sk_test_xxx', [
    'timeout' => 30,
    'connect_timeout' => 10,
    'idempotency_key' => 'unique-request-id',
]);
```

## Available Services

| Service | Description |
|---------|-------------|
| `paymentIntents` | Payment Intents API |
| `paymentMethods` | Payment Methods API |
| `payments` | Payments API |
| `refunds` | Refunds API |
| `customers` | Customers API (v1) |
| `customersV2` | Customers API (v2) |
| `checkoutSessions` | Checkout Sessions API |
| `links` | Payment Links API |
| `webhooks` | Webhooks API |
| `subscriptions` | Subscriptions API |
| `plans` | Plans API |
| `invoices` | Invoices API |
| `qrPh` | QR Ph API |
| `reviews` | Fraud Reviews API |
| `rules` | Fraud Rules API |
| `scores` | Fraud Scores API |
| `wallets` | Wallets API |
| `transfers` | Transfers API |
| `ledgers` | Ledgers API |
| `rates` | Forex Rates API |
| `quotes` | Forex Quotes API |
| `cards` | Card Issuing API |
| `cardPrograms` | Card Programs API |
| `cardholders` | Cardholders API |
| `workflows` | Workflows API |
| `policies` | Policies API |

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@example.com instead of using the issue tracker.

## Credits

- [Leodyver Semilla](https://github.com/leodyversemilla07)
- [All Contributors](../../contributors)

## License

MIT License. See [LICENSE](LICENSE).

## Resources

- [PayMongo API Documentation](https://developers.paymongo.com)
- [PayMongo Dashboard](https://dashboard.paymongo.com)