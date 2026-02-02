<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class WorkflowAuthToken extends BaseEntity
{
    public string $id;
    public string $token;
    public int $expires_at;
    public bool $livemode;
    public int $created_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->token = $attributes['token'];
        $this->expires_at = $attributes['expires_at'];
        $this->livemode = $attributes['livemode'];
        $this->created_at = $attributes['created_at'];
    }
}
