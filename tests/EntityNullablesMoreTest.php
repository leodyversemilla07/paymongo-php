<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\Customer;
use Paymongo\Entities\PaymentMethod;
use Paymongo\Entities\Plan;
use Paymongo\Entities\Subscription;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityNullablesMoreTest extends TestCase
{
    public function testPaymentMethodOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'pm_test_123',
                'attributes' => [
                    'billing' => null,
                    'details' => [],
                    'livemode' => false,
                    'metadata' => null,
                    'type' => 'card',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $method = new PaymentMethod($resource);

        $this->assertNull($method->billing);
        $this->assertNull($method->metadata);
    }

    public function testCustomerOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'cus_test_123',
                'attributes' => [
                    'email' => null,
                    'livemode' => false,
                    'metadata' => null,
                    'name' => null,
                    'phone' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $customer = new Customer($resource);

        $this->assertNull($customer->email);
        $this->assertNull($customer->organization_id);
        $this->assertNull($customer->first_name);
        $this->assertNull($customer->last_name);
        $this->assertNull($customer->phone);
    }

    public function testPlanOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'plan_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'billing_cycle_anchor' => null,
                    'currency' => 'PHP',
                    'interval' => 'month',
                    'interval_count' => 1,
                    'livemode' => false,
                    'metadata' => null,
                    'name' => 'Basic',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $plan = new Plan($resource);

        $this->assertNull($plan->metadata);
    }

    public function testSubscriptionOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'sub_test_123',
                'attributes' => [
                    'billing' => null,
                    'cancel_at' => null,
                    'cancel_at_period_end' => null,
                    'cancelled_at' => null,
                    'currency' => 'PHP',
                    'current_period_end' => 0,
                    'current_period_start' => 0,
                    'customer_id' => 'cus_test_123',
                    'livemode' => false,
                    'metadata' => null,
                    'payment_method_id' => 'pm_test_123',
                    'plan_id' => 'plan_test_123',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $subscription = new Subscription($resource);

        $this->assertNull($subscription->billing);
        $this->assertNull($subscription->cancel_at);
        $this->assertNull($subscription->canceled_at);
        $this->assertNull($subscription->metadata);
    }
}
