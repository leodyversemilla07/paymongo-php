<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WebhookSignatureTest extends TestCase
{
    public function testConstructEventValidatesSignature(): void
    {
        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
        $header = "t={$timestamp},te={$signature},li=";

        $event = $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => $header,
            'webhook_secret_key' => $secret
        ]);

        $this->assertSame('evt_test_123', $event->id);
    }

    public function testConstructEventRejectsInvalidSignature(): void
    {
        $this->expectException(\Paymongo\Exceptions\SignatureVerificationException::class);

        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $timestamp = time();
        $header = "t={$timestamp},te=invalid,li=";

        $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => $header,
            'webhook_secret_key' => $secret
        ]);
    }
}
