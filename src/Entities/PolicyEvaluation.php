<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class PolicyEvaluation extends BaseEntity
{
    public string $id;
    public string $decision;
    public ?string $description;
    public bool $livemode;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->decision = self::requireAttr($attributes, 'decision');
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
    }
}
