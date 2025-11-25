<?php

declare(strict_types=1);

namespace Support\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

class Caster extends Model
{
    public $timestamps = false;

    protected static $isBroadcasting = false;

    public function forceFill(array $attributes)
    {
        $sanitized = collect($attributes)->filter(
            fn ($value, $key) => $this->hasCast($key)
        )->map(
            fn ($value) => $this->sanitize($value)
        );

        return parent::forceFill($sanitized->all());
    }

    /**
     * Jsonable casters are unsafe if the value is a json string as it will
     * be encoded again breaking the original structure. We will inspect
     * for this and decode it first if necessary.
     */
    private function sanitize(mixed $value): mixed
    {
        return match ($value instanceof JsonSerializable) {
            true => $value->jsonSerialize(),
            false => match (false) {
                is_string($value) => $value,
                json_validate($value) => $value,
                default => with(
                    json_decode($value, associative: true),
                    fn ($decoded) => match (true) {
                        is_array($decoded), is_object($decoded) => $decoded, // @phpstan-ignore-line
                        default => $value,
                    }
                )
            }
        };
    }

    /**
     * @param  array<array-key, string>|mixed  $attributes
     */
    public function only($attributes)
    {
        return collect(is_array($attributes) ? $attributes : func_get_args())->filter(
            fn ($key) => $this->hasCast($key) && $this->offsetExists($key)
        )->mapWithKeys(
            fn ($key) => [$key => $this->$key]
        )->all();
    }
}
