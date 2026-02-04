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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->amount_due = self::requireAttr($attributes, 'amount_due');
        $this->billing = empty(self::attr($attributes, 'billing')) ? null : new Billing($attributes['billing']);
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->due_date = self::attr($attributes, 'due_date');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->line_items = self::requireAttr($attributes, 'line_items');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->paid_at = self::attr($attributes, 'paid_at');
        $this->status = self::requireAttr($attributes, 'status');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
