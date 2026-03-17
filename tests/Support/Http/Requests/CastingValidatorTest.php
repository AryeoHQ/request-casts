<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ValidatedInput;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Requests\Caster;
use Support\Http\Requests\CastingValidator;
use Tests\TestCase;

class CastingValidatorTest extends TestCase
{
    #[Test]
    public function it_overlays_casted_values_on_validated(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer'])->forceFill(['age' => '25']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25', 'name' => 'John'], ['age' => 'required', 'name' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $validated = $decorator->validated();

        $this->assertSame(25, $validated['age']);
        $this->assertSame('John', $validated['name']);
    }

    #[Test]
    public function it_only_casts_validated_keys(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer', 'score' => 'float'])->forceFill(['age' => '25', 'score' => '9.5']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25', 'score' => '9.5'], ['age' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $validated = $decorator->validated();

        $this->assertSame(25, $validated['age']);
        $this->assertArrayNotHasKey('score', $validated);
    }

    #[Test]
    public function it_returns_validated_input_from_safe(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer'])->forceFill(['age' => '25']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25'], ['age' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $safe = $decorator->safe();

        $this->assertInstanceOf(ValidatedInput::class, $safe);
        $this->assertSame(25, $safe->all()['age']);
    }

    #[Test]
    public function it_returns_filtered_array_from_safe_with_keys(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer', 'score' => 'float'])->forceFill(['age' => '25', 'score' => '9.5']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25', 'score' => '9.5'], ['age' => 'required', 'score' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $safe = $decorator->safe(['age']);

        $this->assertIsArray($safe);
        $this->assertSame(25, $safe['age']);
        $this->assertArrayNotHasKey('score', $safe);
    }

    #[Test]
    public function it_delegates_fails(): void
    {
        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], ['name' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $this->assertTrue($decorator->fails());
    }

    #[Test]
    public function it_delegates_failed(): void
    {
        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], ['name' => 'required']);
        $inner->fails();

        $decorator = new CastingValidator($inner, $caster);

        $this->assertArrayHasKey('name', $decorator->failed());
    }

    #[Test]
    public function it_delegates_errors(): void
    {
        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], ['name' => 'required']);
        $inner->fails();

        $decorator = new CastingValidator($inner, $caster);

        $this->assertTrue($decorator->errors()->has('name'));
    }

    #[Test]
    public function it_delegates_get_message_bag(): void
    {
        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], ['name' => 'required']);
        $inner->fails();

        $decorator = new CastingValidator($inner, $caster);

        $this->assertTrue($decorator->getMessageBag()->has('name'));
    }

    #[Test]
    public function it_delegates_validate_and_throws_on_failure(): void
    {
        $this->expectException(ValidationException::class);

        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], ['name' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $decorator->validate();
    }

    #[Test]
    public function it_returns_casted_values_from_validate(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer'])->forceFill(['age' => '25']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25', 'name' => 'John'], ['age' => 'required', 'name' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $validated = $decorator->validate();

        $this->assertSame(25, $validated['age']);
        $this->assertSame('John', $validated['name']);
    }

    #[Test]
    public function it_delegates_get_exception_via_magic_call(): void
    {
        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], []);

        $decorator = new CastingValidator($inner, $caster);

        $this->assertSame(ValidationException::class, $decorator->getException()); // @phpstan-ignore method.notFound
    }

    #[Test]
    public function it_delegates_after(): void
    {
        $called = false;

        $caster = Caster::make()->mergeCasts([]); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make([], []);

        $decorator = new CastingValidator($inner, $caster);

        $result = $decorator->after(function () use (&$called): void {
            $called = true;
        });

        $this->assertSame($decorator, $result);

        $decorator->fails();

        $this->assertTrue($called);
    }

    #[Test]
    public function it_leaves_non_casted_values_untouched(): void
    {
        $caster = Caster::make()->mergeCasts(['age' => 'integer'])->forceFill(['age' => '25']); // @phpstan-ignore larastan.noModelMake

        $inner = Validator::make(['age' => '25', 'name' => 'John'], ['age' => 'required', 'name' => 'required']);

        $decorator = new CastingValidator($inner, $caster);

        $validated = $decorator->validated();

        $this->assertIsString($validated['name']);
    }
}
