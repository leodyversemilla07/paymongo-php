<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\LedgerTransaction;
use Paymongo\Entities\PolicyEvaluation;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsMore5Test extends TestCase
{
    public function testPolicyEvaluationMissingDecisionThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'pe_test_123',
                'attributes' => [
                    'livemode' => false,
                    'created_at' => 0,
                ],
            ],
        ]);

        new PolicyEvaluation($resource);
    }

    public function testLedgerTransactionMissingCurrencyThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'lt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'entry_type' => 'credit',
                    'livemode' => false,
                    'status' => 'posted',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new LedgerTransaction($resource);
    }
}
