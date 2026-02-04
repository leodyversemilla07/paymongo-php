<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class ReceivingInstitution extends BaseEntity
{
    public string $id;
    public string $bank_code;
    public string $name;
    public string $type;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->bank_code = self::requireAttr($attributes, 'bank_code');
        $this->name = self::requireAttr($attributes, 'name');
        $this->type = self::requireAttr($attributes, 'type');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
