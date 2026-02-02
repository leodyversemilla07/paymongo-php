<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class RuleAttribute extends BaseEntity
{
    public string $id;
    public ?string $description;
    public bool $livemode;
    public string $name;
    public string $type;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->name = $attributes['name'];
        $this->type = $attributes['type'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
