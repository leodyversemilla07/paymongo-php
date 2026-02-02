<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Webhook.
 */
class Webhook extends BaseEntity
{
    public string $id;
    public bool $livemode;
    public string $secret_key;
    /** @var array<string> */
    public array $events;
    public string $url;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->livemode = $attributes['livemode'];
        $this->secret_key = $attributes['secret_key'];
        $this->events = $attributes['events'];
        $this->url = $attributes['url'];
        $this->status = $attributes['status'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}