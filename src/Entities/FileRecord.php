<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class FileRecord extends BaseEntity
{
    public string $id;
    public string $filename;
    public bool $livemode;
    public string $purpose;
    public string $status;
    public ?string $url;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->filename = self::requireAttr($attributes, 'filename');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->purpose = self::requireAttr($attributes, 'purpose');
        $this->status = self::requireAttr($attributes, 'status');
        $this->url = self::attr($attributes, 'url');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
