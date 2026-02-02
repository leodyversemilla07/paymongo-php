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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->billing = empty($attributes['billing']) ? null : new Billing(new ApiResource($attributes['billing']));
        $this->cancel_at = $attributes['cancel_at'];
        $this->canceled_at = $attributes['canceled_at'];
        $this->current_period_end = $attributes['current_period_end'];
        $this->current_period_start = $attributes['current_period_start'];
        $this->customer = $attributes['customer'];
        $this->livemode = $attributes['livemode'];
        $this->next_billing_at = $attributes['next_billing_at'];
        $this->payment_method = $attributes['payment_method'];
        $this->plan = $attributes['plan'];
        $this->reference_number = $attributes['reference_number'];
        $this->start_date = $attributes['start_date'];
        $this->status = $attributes['status'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
