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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->requirements = self::requireAttr($attributes, 'requirements');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
