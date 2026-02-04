<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Plan.
 */
class Plan extends BaseEntity
{
    public string $id;
    public int $amount;
    public string $currency;
    public string $interval;
    public int $interval_count;
    public bool $livemode;
    public string $name;
    public ?string $billing_statement_descriptor;
    public ?string $description;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->interval = self::requireAttr($attributes, 'interval');
        $this->interval_count = self::requireAttr($attributes, 'interval_count');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->name = self::requireAttr($attributes, 'name');
        $this->billing_statement_descriptor = self::attr($attributes, 'billing_statement_descriptor');
        $this->description = self::attr($attributes, 'description');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
