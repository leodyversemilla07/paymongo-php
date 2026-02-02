<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Ledger extends BaseEntity
{
    public string $id;
    public string $currency;
    public ?string $description;
    public bool $livemode;
    public string $name;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->currency = $attributes['currency'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->name = $attributes['name'];
        $this->status = $attributes['status'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
