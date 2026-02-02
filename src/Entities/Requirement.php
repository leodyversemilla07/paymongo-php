<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Requirement extends BaseEntity
{
    public string $id;
    /** @var array<string, mixed> */
    public array $requirements;
    public bool $livemode;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->requirements = $attributes['requirements'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
