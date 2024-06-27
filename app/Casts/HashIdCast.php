<?php

namespace App\Casts;

use App\Traits\HashIdConverter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class HashIdCast implements CastsAttributes
{
    use HashIdConverter;

    public function __construct(public string $keyName)
    {
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $this->hash($value, $this->keyName);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
//        return $this->deHash($value, $this->keyName);
    }
}
