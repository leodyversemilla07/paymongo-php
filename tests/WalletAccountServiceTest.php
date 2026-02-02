<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WalletAccountServiceTest extends TestCase
{
    public function testListWalletAccountsBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => []
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->walletAccounts->all();

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallet_accounts', $fake->lastRequest['url']);
    }
}
