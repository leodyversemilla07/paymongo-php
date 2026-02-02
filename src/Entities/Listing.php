<?php

declare(strict_types=1);

namespace Paymongo\Entities;

/**
 * Represents a paginated list of resources.
 */
class Listing
{
    public bool $has_more;
    
    /** @var array<BaseEntity> */
    public array $data;

    /**
     * @param array{has_more: bool|int|null, data: array<BaseEntity>} $data
     */
    public function __construct(array $data)
    {
        $this->has_more = in_array($data['has_more'], [true, 1], true);
        $this->data = $data['data'];
    }
}