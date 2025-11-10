<?php

declare(strict_types=1);

namespace Support\Http\Requests\Contracts;

interface CastableData
{
    /** @return array<array-key, string> */
    public function casts(): array;

    /** @param  array<string, string>  $casts */
    public function mergeCasts(array $casts): static;

    /**
     * @param  mixed  $keys
     * @return array<array-key, mixed>
     */
    public function all($keys = null): array;
}
