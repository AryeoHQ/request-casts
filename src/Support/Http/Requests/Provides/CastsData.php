<?php

declare(strict_types=1);

namespace Support\Http\Requests\Provides;

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
        get => $this->casted ??= Caster::make()->mergeCasts($this->casts)->forceFill(parent::all());
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
