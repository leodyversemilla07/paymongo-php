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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->name = self::requireAttr($attributes, 'name');
        $this->type = self::requireAttr($attributes, 'type');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
