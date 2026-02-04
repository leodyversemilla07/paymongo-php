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

    public function testConstructEventRejectsInvalidHeaderFormat(): void
    {
        $this->expectException(\Paymongo\Exceptions\UnexpectedValueException::class);

        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $header = "t=123";

        $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => $header,
            'webhook_secret_key' => $secret
        ]);
    }

    public function testConstructEventRejectsMissingTimestamp(): void
    {
        $this->expectException(\Paymongo\Exceptions\UnexpectedValueException::class);

        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $header = "te=abc,li=";

        $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => $header,
            'webhook_secret_key' => $secret
        ]);
    }

    public function testConstructEventRejectsMissingSignatures(): void
    {
        $this->expectException(\Paymongo\Exceptions\UnexpectedValueException::class);

        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $header = "t=123";

        $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => $header,
            'webhook_secret_key' => $secret
        ]);
    }

    public function testConstructEventRejectsNonStringSignatureHeader(): void
    {
        $this->expectException(\Paymongo\Exceptions\UnexpectedValueException::class);

        $client = new \Paymongo\PaymongoClient('sk_test_key');
        $payload = file_get_contents(__DIR__ . '/fixtures/sample_event.json');
        $secret = 'whsec_test';

        $client->webhooks->constructEvent([
            'payload' => $payload,
            'signature_header' => ['t=123', 'te=abc', 'li='],
            'webhook_secret_key' => $secret
        ]);
    }
}
