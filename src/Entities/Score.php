<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Score extends BaseEntity
{
    public string $id;
    public ?string $decision;
    public string $entity_id;
    public string $entity_type;
    public bool $livemode;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public float $score;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->decision = self::attr($attributes, 'decision');
        $this->entity_id = self::requireAttr($attributes, 'entity_id');
        $this->entity_type = self::requireAttr($attributes, 'entity_type');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->score = self::requireAttr($attributes, 'score');
        $this->status = self::requireAttr($attributes, 'status');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
