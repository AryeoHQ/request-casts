<?php

declare(strict_types=1);

namespace Support\Http\Requests;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Http\Requests\Contracts\CastableData;

class Caster extends Model
{
    private Request&CastableData $request;

    public $timestamps = false;

    protected static $isBroadcasting = false;

    public function from(Request&CastableData $request): static
    {
        $this->request = $request;

        return $this;
    }

    public function prepare(): static
    {
        return static::withoutEvents(
            fn () => $this->mergeCasts([
                ...$this->request->casts,
            ])->forceFill([
                ...$this->sanitize($this->request)->toArray(),
                ...$this->request->allFiles(),
            ])
        );
    }

    /**
     * Jsonable casters are unsafe if the value is a json string as it will
     * be encoded again breaking the original structure. We will inspect
     * for this and decode it first if necessary.
     *
     * @return Collection<string, mixed>
     */
    private function sanitize(Request $request): Collection
    {
        return $request->collect()->map(function ($value) {
            return match (false) {
                is_string($value) => $value,
                json_validate($value) => $value,
                default => with(
                    json_decode($value, associative: true),
                    fn ($decoded) => match (true) {
                        is_array($decoded), is_object($decoded) => $decoded, // @phpstan-ignore-line
                        default => $value,
                    }
                )
            };
        });
    }
}
