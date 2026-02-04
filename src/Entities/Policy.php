<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Policy extends BaseEntity
{
    public string $id;
    public ?string $description;
    public bool $livemode;
    public string $name;
    /** @var array<int, mixed> */
    public array $rules;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->name = self::requireAttr($attributes, 'name');
        $this->rules = self::requireAttr($attributes, 'rules');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
