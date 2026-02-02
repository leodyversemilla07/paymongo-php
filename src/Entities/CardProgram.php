<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class CardProgram extends BaseEntity
{
    public string $id;
    public string $name;
    public string $type;
    public string $currency;
    public bool $livemode;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->name = $attributes['name'];
        $this->type = $attributes['type'];
        $this->currency = $attributes['currency'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
