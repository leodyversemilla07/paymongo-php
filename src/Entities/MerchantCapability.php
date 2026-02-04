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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->code = self::requireAttr($attributes, 'code');
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
