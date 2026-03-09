<?php

declare(strict_types=1);

namespace Support\Http\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Support\Http\Requests\Caster;

/**
 * @implements CastsAttributes<array<array-key, mixed>, array<array-key, mixed>>
 */
class Nested implements Castable, CastsAttributes
{
    /** @var array<string, mixed> */
    protected array $normalized;

    /**
     * @param  array<string, string>  $casts
     */
    public function __construct(protected array $casts)
    {
        $this->normalized = collect(Arr::undot($casts))->map(
            fn (mixed $value) => is_array($value) ? static::make($value) : $value
        )->all();
    }

    /**
     * @param  array<string, string>  $casts
     */
    public static function make(array $casts): string
    {
        return static::class.':'.base64_encode(json_encode($casts, JSON_THROW_ON_ERROR));
    }

    /**
     * @param  string[]  $arguments
     * @return CastsAttributes<array<array-key, mixed>, array<array-key, mixed>>
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new self(
            casts: json_decode(base64_decode($arguments[0], true), true),
        );
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (array_key_exists('*', $this->normalized)) {
            $nested = new self(Arr::undot($this->casts)['*']);

            $value = collect($value)->map(fn (mixed $item) => is_array($item)
                ? $nested->get($model, $key, $item, $attributes)
                : $item
            )->all();
        }

        $casts = collect($this->normalized)->except('*');

        return $casts->isEmpty() ? $value : $this->cast($value, $casts->all());
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [$key => $value];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, string>  $casts
     * @return array<string, mixed>
     */
    protected function cast(array $data, array $casts): array
    {
        $caster = Caster::make()->mergeCasts($casts)->forceFill($data); // @phpstan-ignore larastan.noModelMake

        return array_merge($data, $caster->only(array_keys($casts)));
    }
}
