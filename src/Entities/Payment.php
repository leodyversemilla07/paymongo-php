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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->billing = is_null($attributes['billing']) ? null : new Billing($attributes['billing']);
        $this->currency = $attributes['currency'];
        $this->description = $attributes['description'];
        $this->fee = $attributes['fee'];
        $this->livemode = $attributes['livemode'];
        $this->net_amount = $attributes['net_amount'];
        $this->statement_descriptor = $attributes['statement_descriptor'];
        $this->status = $attributes['status'];
        $this->available_at = $attributes['available_at'];
        $this->created_at = $attributes['created_at'];
        $this->paid_at = $attributes['paid_at'];
        $this->payout = $attributes['payout'];
        $this->updated_at = $attributes['updated_at'];
        $this->metadata = $attributes['metadata'];
        $this->source = $attributes['source'];
        $this->tax_amount = $attributes['tax_amount'];
        $this->payment_intent_id = $attributes['payment_intent_id'];
        $this->refunds = [];

        if (is_array($attributes['refunds']) && !empty($attributes['refunds'])) {
            foreach ($attributes['refunds'] as $refund) {
                $rowApiResource = new ApiResource($refund);
                $this->refunds[] = new Refund($rowApiResource);
            }
        }

        $this->taxes = $attributes['taxes'];
    }
}