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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->balance_transaction_id = self::attr($attributes, 'balance_transaction_id');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->payment_id = self::requireAttr($attributes, 'payment_id');
        $this->payout_id = self::attr($attributes, 'payout_id');
        $this->notes = self::attr($attributes, 'notes');
        $this->reason = self::requireAttr($attributes, 'reason');
        $this->status = self::requireAttr($attributes, 'status');
        $this->available_at = self::attr($attributes, 'available_at');
        $this->refunded_at = self::attr($attributes, 'refunded_at');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}