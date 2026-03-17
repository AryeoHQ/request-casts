<?php

declare(strict_types=1);

namespace Support\Http\Requests;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\ValidatedInput;

class CastingValidator implements Validator
{
    private Validator $inner;

    private Caster $caster;

    public function __construct(Validator $inner, Caster $caster)
    {
        $this->inner = $inner;
        $this->caster = $caster;
    }

    /**
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $validated = $this->inner->validated();

        return [...$validated, ...$this->caster->only(array_keys($validated))];
    }

    /**
     * @param  array<array-key, string>|null  $keys
     * @return ($keys is null ? \Illuminate\Support\ValidatedInput : array<string, mixed>)
     */
    public function safe(null|array $keys = null): ValidatedInput|array
    {
        $validated = $this->validated();

        return is_array($keys) ? (new ValidatedInput($validated))->only($keys) : new ValidatedInput($validated);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(): array
    {
        $this->inner->validate();

        return $this->validated();
    }

    public function fails(): bool
    {
        return $this->inner->fails();
    }

    /**
     * @return array<string, mixed>
     */
    public function failed(): array
    {
        return $this->inner->failed();
    }

    /**
     * @param  string|array<array-key, mixed>  $attribute
     * @param  string|array<array-key, mixed>  $rules
     */
    public function sometimes(mixed $attribute, mixed $rules, callable $callback): static
    {
        $this->inner->sometimes($attribute, $rules, $callback);

        return $this;
    }

    /**
     * @param  callable|string  $callback
     */
    public function after(mixed $callback): static
    {
        $this->inner->after($callback);

        return $this;
    }

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->inner->errors();
    }

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function getMessageBag(): MessageBag
    {
        return $this->inner->getMessageBag(); // @phpstan-ignore return.type
    }

    /**
     * @param  array<array-key, mixed>  $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->inner->{$method}(...$parameters);
    }

    public function __get(string $name): mixed
    {
        return $this->inner->{$name};
    }
}
