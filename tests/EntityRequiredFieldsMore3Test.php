<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerTransaction;
use Paymongo\Entities\Policy;
use Paymongo\Entities\Webhook;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsMore3Test extends TestCase
{
    public function testLedgerTransactionMissingEntryTypeThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'lt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'status' => 'posted',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new LedgerTransaction($resource);
    }

    public function testWebhookMissingUrlThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'wh_test_123',
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.paid'],
                    'status' => 'enabled',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Webhook($resource);
    }

    public function testPolicyMissingNameThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'policy_test_123',
                'attributes' => [
                    'livemode' => false,
                    'rules' => [],
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Policy($resource);
    }
}
