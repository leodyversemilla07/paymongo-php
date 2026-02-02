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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->currency = $attributes['currency'];
        $this->interval = $attributes['interval'];
        $this->interval_count = $attributes['interval_count'];
        $this->livemode = $attributes['livemode'];
        $this->name = $attributes['name'];
        $this->billing_statement_descriptor = $attributes['billing_statement_descriptor'];
        $this->description = $attributes['description'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
