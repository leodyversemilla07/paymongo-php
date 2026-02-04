<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\Invoice;
use Paymongo\Entities\Transfer;
use Paymongo\Entities\Wallet;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsMore2Test extends TestCase
{
    public function testTransferMissingAmountThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'tr_test_123',
                'attributes' => [
                    'currency' => 'PHP',
                    'livemode' => false,
                    'status' => 'pending',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Transfer($resource);
    }

    public function testWalletMissingBalanceThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'wallet_test_123',
                'attributes' => [
                    'available_balance' => 5000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'name' => 'Main Wallet',
                    'type' => 'merchant',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Wallet($resource);
    }

    public function testInvoiceMissingAmountThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'in_test_123',
                'attributes' => [
                    'amount_due' => 1000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'line_items' => [],
                    'status' => 'draft',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Invoice($resource);
    }
}
