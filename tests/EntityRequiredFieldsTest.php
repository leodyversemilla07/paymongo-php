<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\Ledger;
use Paymongo\Entities\Policy;
use Paymongo\Entities\Workflow;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsTest extends TestCase
{
    public function testWorkflowMissingRequiredFieldThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'wf_test_123',
                'attributes' => [
                    'description' => 'Test workflow',
                    'livemode' => false,
                    'status' => 'active',
                    'steps' => [],
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Workflow($resource);
    }

    public function testPolicyMissingRequiredFieldThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'policy_test_123',
                'attributes' => [
                    'description' => 'Test policy',
                    'livemode' => false,
                    'rules' => [],
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Policy($resource);
    }

    public function testLedgerMissingRequiredFieldThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'ledger_test_123',
                'attributes' => [
                    'description' => 'Ledger',
                    'livemode' => false,
                    'name' => 'Ledger',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Ledger($resource);
    }
}
