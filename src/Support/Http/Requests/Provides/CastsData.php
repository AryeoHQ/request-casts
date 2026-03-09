<?php

declare(strict_types=1);

namespace Support\Http\Requests\Provides;

use Illuminate\Support\ValidatedInput;
use Support\Http\Requests\Caster;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
trait CastsData
{
    /**
     * @var array<string, mixed>
     */
    public private(set) null|array $casts = null {
        get => $this->casts ??= array_merge($this->casts(), $this->casts ?? []);
    }

    private Caster $casted {
        get => $this->casted ??= Caster::make()->mergeCasts($this->casts)->forceFill(parent::all()); // @phpstan-ignore larastan.noModelMake
    }

    /**
     * @param  mixed  $keys
     * @return array<array-key, mixed>
     */
    public function all($keys = null): array
    {
        return [
            ...parent::all($keys),
            ...$this->casted->only($keys ?? $this->keys()),
        ];
    }

    /**
     * @return array<array-key, mixed>
     */
    public function validationData(): array
    {
        return parent::all();
    }

    /**
     * @param  array<array-key, string>|int|string|null  $key
     * @param  mixed  $default
     */
    public function validated($key = null, $default = null): mixed
    {
        $validated = $this->validator->validated();

        return data_get(
            [...$validated, ...$this->casted->only(array_keys($validated))],
            $key,
            $default
        );
    }

    /**
     * @param  array<array-key, string>|null  $keys
     * @return ($keys is null ? ValidatedInput : array<string, mixed>)
     */
    public function safe(null|array $keys = null): ValidatedInput|array
    {
        $validated = $this->validated();

        return is_array($keys)
            ? (new ValidatedInput($validated))->only($keys)
            : new ValidatedInput($validated);
    }

    /**
     * @param  array<array-key, mixed>  $input
     */
    public function merge(array $input): self
    {
        $this->casted->forceFill($input);

        return parent::merge($input);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<array-key, string>
     */
    public function casts(): array
    {
        return [];
    }

    /**
     * @param  array<string, string>  $casts
     */
    public function mergeCasts(array $casts): static
    {
        $this->casts = array_merge($this->casts, $casts);

        $this->casted->mergeCasts($this->casts);

        return $this;
    }
}
