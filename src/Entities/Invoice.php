<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Invoice extends BaseEntity
{
    public string $id;
    public int $amount;
    public int $amount_due;
    public ?Billing $billing;
    public string $currency;
    public ?int $due_date;
    public bool $livemode;
    /** @var array<int, mixed> */
    public array $line_items;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public ?int $paid_at;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->amount_due = $attributes['amount_due'];
        $this->billing = empty($attributes['billing']) ? null : new Billing(new ApiResource($attributes['billing']));
        $this->currency = $attributes['currency'];
        $this->due_date = $attributes['due_date'];
        $this->livemode = $attributes['livemode'];
        $this->line_items = $attributes['line_items'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->paid_at = $attributes['paid_at'];
        $this->status = $attributes['status'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
