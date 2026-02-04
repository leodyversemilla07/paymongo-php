<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Entities\CheckoutSession;
use Paymongo\Entities\Invoice;
use Paymongo\Entities\Refund;
use Paymongo\Entities\Webhook;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class EntityNullablesExtendedTest extends TestCase
{
    public function testCheckoutSessionOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'cs_test_123',
                'attributes' => [
                    'billing' => null,
                    'cancel_url' => null,
                    'description' => null,
                    'line_items' => [],
                    'livemode' => false,
                    'payment_method_types' => ['card'],
                    'reference_number' => null,
                    'success_url' => null,
                    'status' => 'active',
                    'url' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $session = new CheckoutSession($resource);

        $this->assertNull($session->billing);
        $this->assertNull($session->cancel_url);
        $this->assertNull($session->description);
        $this->assertNull($session->reference_number);
        $this->assertNull($session->success_url);
        $this->assertNull($session->url);
        $this->assertNull($session->metadata);
    }

    public function testWebhookOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'wh_test_123',
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.paid'],
                    'url' => 'https://example.com/webhook',
                    'status' => 'enabled',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $webhook = new Webhook($resource);

        $this->assertNull($webhook->metadata);
    }

    public function testRefundOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 're_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'balance_transaction_id' => null,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'metadata' => null,
                    'payment_id' => 'pay_test_123',
                    'reason' => 'requested_by_customer',
                    'status' => 'pending',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $refund = new Refund($resource);

        $this->assertNull($refund->balance_transaction_id);
        $this->assertNull($refund->metadata);
        $this->assertSame('requested_by_customer', $refund->reason);
    }

    public function testInvoiceOptionalFieldsDefaultToNull(): void
    {
        $resource = new ApiResource([
            'data' => [
                'id' => 'in_test_123',
                'attributes' => [
                    'amount_due' => 1000,
                    'amount' => 1000,
                    'billing' => null,
                    'currency' => 'PHP',
                    'description' => null,
                    'livemode' => false,
                    'line_items' => [],
                    'metadata' => null,
                    'status' => 'draft',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]);

        $invoice = new Invoice($resource);

        $this->assertNull($invoice->billing);
        $this->assertNull($invoice->metadata);
    }
}
