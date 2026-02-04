<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\Payment;
use Paymongo\Entities\PaymentIntent;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityNullablesTest extends TestCase
{
    public function testPaymentIntentOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'pi_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'capture_type' => 'automatic',
                    'client_key' => 'pi_client_key',
                    'currency' => 'PHP',
                    'livemode' => false,
                    'status' => 'awaiting_payment_method',
                    'payment_method_allowed' => ['card'],
                    'payments' => [],
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $intent = new PaymentIntent($resource);

        $this->assertNull($intent->description);
        $this->assertNull($intent->last_payment_error);
        $this->assertNull($intent->statement_descriptor);
        $this->assertNull($intent->next_action);
        $this->assertNull($intent->payment_method_options);
        $this->assertNull($intent->metadata);
        $this->assertNull($intent->setup_future_usage);
    }

    public function testPaymentOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'pay_test_123',
                'attributes' => [
                    'amount' => 1000,
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

        $payment = new Payment($resource);

        $this->assertNull($payment->billing);
        $this->assertNull($payment->description);
        $this->assertNull($payment->statement_descriptor);
        $this->assertNull($payment->available_at);
        $this->assertNull($payment->paid_at);
        $this->assertNull($payment->payout);
        $this->assertNull($payment->metadata);
        $this->assertNull($payment->source);
        $this->assertNull($payment->tax_amount);
        $this->assertNull($payment->payment_intent_id);
        $this->assertNull($payment->taxes);
    }
}
