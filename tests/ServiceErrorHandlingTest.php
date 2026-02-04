<?php

declare(strict_types=1);

use Paymongo\PaymongoClient;
use Paymongo\Exceptions\AuthenticationException;
use Paymongo\Exceptions\InvalidRequestException;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceErrorHandlingTest extends TestCase
{
    public function testPaymentIntentCreatePropagatesAuthenticationException(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queue[] = new AuthenticationException([
            'errors' => [[
                'detail' => 'Invalid API key'
            ]]
        ]);

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $this->expectException(AuthenticationException::class);

        $client->paymentIntents->create([
            'amount' => 1000,
            'currency' => 'PHP',
            'payment_method_allowed' => ['card']
        ]);
    }

    public function testRefundCreatePropagatesInvalidRequestException(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queue[] = new InvalidRequestException([
            'errors' => [[
                'detail' => 'Invalid amount'
            ]]
        ]);

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $this->expectException(InvalidRequestException::class);

        $client->refunds->create([
            'amount' => -1,
            'payment_id' => 'pay_test_123'
        ]);
    }
}
