<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Refund.
 */
class Refund extends BaseEntity
{
    public string $id;
    public int $amount;
    public ?string $balance_transaction_id;
    public bool $livemode;
    public string $payment_id;
    public ?string $payout_id;
    public ?string $notes;
    public string $reason;
    public string $status;
    public ?int $available_at;
    public ?int $refunded_at;
    public string $currency;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->balance_transaction_id = $attributes['balance_transaction_id'];
        $this->livemode = $attributes['livemode'];
        $this->payment_id = $attributes['payment_id'];
        $this->payout_id = $attributes['payout_id'];
        $this->notes = $attributes['notes'];
        $this->reason = $attributes['reason'];
        $this->status = $attributes['status'];
        $this->available_at = $attributes['available_at'];
        $this->refunded_at = $attributes['refunded_at'];
        $this->currency = $attributes['currency'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}