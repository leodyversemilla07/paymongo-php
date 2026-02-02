<?php

declare(strict_types=1);

/**
 * PayMongo PHP SDK Example Usage
 * 
 * Use initialize.php when not using Composer's autoload.
 * If using Composer, require the autoloader instead:
 * require_once 'vendor/autoload.php';
 */
require_once __DIR__ . '/initialize.php';

use Paymongo\PaymongoClient;
use Paymongo\Exceptions\AuthenticationException;
use Paymongo\Exceptions\InvalidRequestException;
use Paymongo\Exceptions\SignatureVerificationException;

// Initialize the client
$client = new PaymongoClient('your secret API key here');

// Error handling for authentication
try {
    $client = new PaymongoClient('invalid secret api key');
    // Some PayMongo API calls
} catch (AuthenticationException $e) {
    // Handle error if API key is invalid
    echo "Authentication failed\n";
}

// Error handling for validation
try {
    $client = new PaymongoClient('secret API key');
    $payment = $client->payments->create([
        // incorrect payload
    ]);
} catch (InvalidRequestException $e) {
    // Handle validation errors
    foreach ($e->getError() as $error) {
        echo $error->code . "\n";
        echo $error->detail . "\n";
    }
}

// Retrieve a payment method
$paymentMethod = $client->paymentMethods->retrieve('insert payment method id here');

// Create a payment intent
$newPaymentIntent = $client->paymentIntents->create([
    'amount' => 10000,
    'currency' => 'PHP',
    'payment_method_allowed' => ['card'],
]);

// Retrieve a payment intent
$paymentIntent = $client->paymentIntents->retrieve('insert payment intent id here');

// Create source
$source = $client->sources->create([
    // insert payload here
]);

// Create a checkout session
$checkoutSession = $client->checkoutSessions->create([
    'line_items' => [
        [
            'name' => 'Product Name',
            'quantity' => 1,
            'amount' => 10000,
            'currency' => 'PHP',
        ]
    ],
    'payment_method_types' => ['card', 'gcash'],
    'success_url' => 'https://example.com/success',
    'cancel_url' => 'https://example.com/cancel',
]);

echo $checkoutSession->checkout_url . "\n";

// Verifying webhook signature
try {
    $payload = @file_get_contents('php://input');
    $signatureHeader = $_SERVER['HTTP_PAYMONGO_SIGNATURE'] ?? '';
    $webhookSecretKey = 'your webhook secret key here';

    $event = $client->webhooks->constructEvent([
        'payload' => $payload,
        'signature_header' => $signatureHeader,
        'webhook_secret_key' => $webhookSecretKey
    ]);

    echo $event->id . "\n";
    echo $event->type . "\n";
    print_r($event->resource);

} catch (SignatureVerificationException $e) {
    // Handle invalid signature error
    echo 'Invalid signature';
}