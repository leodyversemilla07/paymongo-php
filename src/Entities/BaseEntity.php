<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Base class for all PayMongo API entities.
 */
abstract class BaseEntity
{
    public string $id;

    /**
     * Create a new entity instance from an API resource.
     */
    abstract public function __construct(ApiResource $apiResource);

    /**
     * Convert the entity to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof BaseEntity) {
                $data[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$key] = array_map(function ($item) {
                    return $item instanceof BaseEntity ? $item->toArray() : $item;
                }, $value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}