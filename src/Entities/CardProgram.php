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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->name = self::requireAttr($attributes, 'name');
        $this->type = self::requireAttr($attributes, 'type');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
