<?php

namespace Novikovvs\Common\DTOs;

use BackedEnum;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDTO implements Arrayable, JsonSerializable
{
    public function toArray(): array
    {
        return collect(get_object_vars($this))->mapWithKeys(function ($value, $key) {
            if ($value instanceof AbstractDTO) {
                $value = $value->toArray();
            } elseif (is_array($value) || $value instanceof Arrayable) {
                if ($value instanceof Collection) {
                    $value = $value->toArray();
                }

                $value = array_map(function (mixed $value) {
                    if ($value instanceof AbstractDTO) {
                        return $value->toArray();
                    }

                    return $value;
                }, $value);
            } elseif ($value instanceof Carbon) {
                $value = $value->toISOString(true);
            } elseif ($value instanceof BackedEnum) {
                $value = $value->value;
            }

            return [Str::snake($key) => $value];
        })->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function isInitialized(string $property): bool
    {
        return array_key_exists(Str::camel($property), get_object_vars($this));
    }
}
