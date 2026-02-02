<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class ChildMerchant extends BaseEntity
{
    public string $id;
    /** @var array<string, mixed> */
    public array $business;
    public bool $livemode;
    public string $status;
    /** @var array<string, mixed> */
    public array $requirements;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->business = $attributes['business'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->requirements = $attributes['requirements'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
