<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Subscription.
 */
class Subscription extends BaseEntity
{
    public string $id;
    public ?Billing $billing;
    public ?int $cancel_at;
    public ?int $canceled_at;
    public ?int $current_period_end;
    public ?int $current_period_start;
    public mixed $customer;
    public bool $livemode;
    public ?int $next_billing_at;
    public mixed $payment_method;
    public mixed $plan;
    public ?string $reference_number;
    public ?int $start_date;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->billing = empty(self::attr($attributes, 'billing')) ? null : new Billing($attributes['billing']);
        $this->cancel_at = self::attr($attributes, 'cancel_at');
        $this->canceled_at = self::attr($attributes, 'canceled_at');
        $this->current_period_end = self::attr($attributes, 'current_period_end');
        $this->current_period_start = self::attr($attributes, 'current_period_start');
        $this->customer = self::attr($attributes, 'customer');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->next_billing_at = self::attr($attributes, 'next_billing_at');
        $this->payment_method = self::attr($attributes, 'payment_method');
        $this->plan = self::attr($attributes, 'plan');
        $this->reference_number = self::attr($attributes, 'reference_number');
        $this->start_date = self::attr($attributes, 'start_date');
        $this->status = self::requireAttr($attributes, 'status');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
