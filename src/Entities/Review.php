<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Review extends BaseEntity
{
    public string $id;
    public ?string $decision;
    public string $entity_id;
    public string $entity_type;
    public bool $livemode;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public ?string $reason;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->decision = $attributes['decision'];
        $this->entity_id = $attributes['entity_id'];
        $this->entity_type = $attributes['entity_type'];
        $this->livemode = $attributes['livemode'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->reason = $attributes['reason'];
        $this->status = $attributes['status'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
