<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Challenge extends BaseEntity
{
    public string $id;
    public string $status;
    public string $type;
    public bool $livemode;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->status = self::requireAttr($attributes, 'status');
        $this->type = self::requireAttr($attributes, 'type');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
