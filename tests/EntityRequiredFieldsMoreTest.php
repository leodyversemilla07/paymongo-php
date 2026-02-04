<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\Payment;
use Paymongo\Entities\PaymentIntent;
use Paymongo\Entities\Plan;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsMoreTest extends TestCase
{
    public function testPaymentIntentMissingAmountThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'pi_test_123',
                'attributes' => [
                    'capture_type' => 'automatic',
                    'client_key' => 'pi_client_key',
                    'currency' => 'PHP',
                    'livemode' => false,
                    'status' => 'awaiting_payment_method',
                    'payment_method_allowed' => ['card'],
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new PaymentIntent($resource);
    }

    public function testPaymentMissingAmountThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'pay_test_123',
                'attributes' => [
                    'currency' => 'PHP',
                    'fee' => 0,
                    'livemode' => false,
                    'net_amount' => 1000,
                    'status' => 'paid',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Payment($resource);
    }

    public function testPlanMissingIntervalThrows(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $resource = new ApiResource([
            'data' => [
                'id' => 'plan_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'interval_count' => 1,
                    'livemode' => false,
                    'name' => 'Basic',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        new Plan($resource);
    }
}
