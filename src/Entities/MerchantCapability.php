<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class MerchantCapability extends BaseEntity
{
    public string $id;
    public string $code;
    public ?string $description;
    public bool $livemode;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->code = $attributes['code'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
