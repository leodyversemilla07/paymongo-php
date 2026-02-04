<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Payment.
 */
class Payment extends BaseEntity
{
    public string $id;
    public int $amount;
    public ?Billing $billing;
    public string $currency;
    public ?string $description;
    public int $fee;
    public bool $livemode;
    public int $net_amount;
    public ?string $statement_descriptor;
    public string $status;
    public ?int $available_at;
    public int $created_at;
    public ?int $paid_at;
    public mixed $payout;
    public int $updated_at;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public mixed $source;
    public ?int $tax_amount;
    public ?string $payment_intent_id;
    /** @var array<Refund> */
    public array $refunds;
    public mixed $taxes;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->billing = ( (self::attr($attributes, 'billing')) === null ) ? null : new Billing($attributes['billing']);
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->description = self::attr($attributes, 'description');
        $this->fee = self::requireAttr($attributes, 'fee');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->net_amount = self::requireAttr($attributes, 'net_amount');
        $this->statement_descriptor = self::attr($attributes, 'statement_descriptor');
        $this->status = self::requireAttr($attributes, 'status');
        $this->available_at = self::attr($attributes, 'available_at');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->paid_at = self::attr($attributes, 'paid_at');
        $this->payout = self::attr($attributes, 'payout');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
        $this->metadata = self::attr($attributes, 'metadata');
        $this->source = self::attr($attributes, 'source');
        $this->tax_amount = self::attr($attributes, 'tax_amount');
        $this->payment_intent_id = self::attr($attributes, 'payment_intent_id');
        $this->refunds = [];

        if (is_array(self::attr($attributes, 'refunds')) && !empty(self::attr($attributes, 'refunds'))) {
            foreach ($attributes['refunds'] as $refund) {
                $rowApiResource = new ApiResource($refund);
                $this->refunds[] = new Refund($rowApiResource);
            }
        }

        $this->taxes = self::attr($attributes, 'taxes');
    }
}