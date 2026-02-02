<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class QrPh extends BaseEntity
{
    public string $id;
    public int $amount;
    public string $currency;
    public ?string $reference_number;
    public ?string $description;
    public bool $livemode;
    public string $status;
    public string $qr_content;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->currency = $attributes['currency'];
        $this->reference_number = $attributes['reference_number'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->qr_content = $attributes['qr_content'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
