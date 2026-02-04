<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class QrTransfer extends BaseEntity
{
    public string $id;
    public int $amount;
    public string $currency;
    public bool $livemode;
    public ?string $reference_number;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $recipient;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->reference_number = self::attr($attributes, 'reference_number');
        $this->status = self::requireAttr($attributes, 'status');
        $this->recipient = self::attr($attributes, 'recipient');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
