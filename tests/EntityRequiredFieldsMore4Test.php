<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerAccount;
use Paymongo\Entities\LedgerEntry;
use Paymongo\Entities\WalletAccount;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsMore4Test extends TestCase
{
    public function testLedgerEntryMissingAccountIdThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'le_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'entry_type' => 'credit',
                    'livemode' => false,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new LedgerEntry($resource);
    }

    public function testLedgerAccountMissingAccountCodeThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'la_test_123',
                'attributes' => [
                    'currency' => 'PHP',
                    'livemode' => false,
                    'name' => 'Cash',
                    'type' => 'asset',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new LedgerAccount($resource);
    }

    public function testWalletAccountMissingAccountNumberThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'wa_test_123',
                'attributes' => [
                    'account_name' => 'Main',
                    'account_type' => 'checking',
                    'bank_code' => 'TEST',
                    'bank_name' => 'Test Bank',
                    'currency' => 'PHP',
                    'livemode' => false,
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new WalletAccount($resource);
    }
}
