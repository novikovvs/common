<?php

namespace Novikovvs\Common\ResourceModels;

use BackedEnum;
use Carbon\Carbon;
use JsonSerializable;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Address;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractResourceModel implements JsonSerializable, Arrayable
{
    protected const SKIP = [];

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $propMap = [];

        foreach (get_object_vars($this) as $propName => $prop) {
            if (!in_array($propName, static::SKIP, true)) {
                $propMap[$propName] = Str::snake($propName);
            }
        }

        $result = [];

        foreach ($propMap as $originName => $transformName) {
            $origin = $this->{$originName};

            if ($origin instanceof AbstractResourceModel || $origin instanceof Arrayable) {
                $origin = $origin->toArray();
            } elseif ($origin instanceof BackedEnum) {
                $origin = $origin->value;
            } elseif ($origin instanceof Carbon) {
                $origin = $origin->toISOString(true);
            } elseif ($origin instanceof Address) {
                $origin = $origin->getAddress();
            }

            $result[$transformName] = $origin;
        }

        return $result;
    }
}
