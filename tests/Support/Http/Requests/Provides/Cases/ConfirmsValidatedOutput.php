<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ValidatedInput;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Casts\Nested;

/**
 * @mixin \Tests\Support\Http\Requests\FormRequestTest
 */
trait ConfirmsValidatedOutput
{
    #[Test]
    public function it_returns_casted_values_from_validated(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
            'name' => 'John',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required', 'name' => 'required'])
        );

        $validated = $this->request->validated();

        $this->assertSame(25, $validated['age']);
        $this->assertSame('John', $validated['name']);
    }

    #[Test]
    public function it_returns_casted_value_from_validated_with_key(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required'])
        );

        $this->assertSame(25, $this->request->validated('age'));
    }

    #[Test]
    public function it_only_returns_validated_keys_from_validated(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
            'score' => 'float',
        ])->merge([
            'age' => '25',
            'score' => '9.5',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required'])
        );

        $validated = $this->request->validated();

        $this->assertSame(25, $validated['age']);
        $this->assertArrayNotHasKey('score', $validated);
    }

    #[Test]
    public function it_returns_casted_values_from_safe_all(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required'])
        );

        $safe = $this->request->safe();

        $this->assertInstanceOf(ValidatedInput::class, $safe);
        $this->assertSame(25, $safe->all()['age']);
    }

    #[Test]
    public function it_returns_casted_subset_from_safe_with_keys(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
            'score' => 'float',
        ])->merge([
            'age' => '25',
            'score' => '9.5',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required', 'score' => 'required'])
        );

        $safe = $this->request->safe(['age']);

        $this->assertIsArray($safe);
        $this->assertSame(25, $safe['age']);
        $this->assertArrayNotHasKey('score', $safe);
    }

    #[Test]
    public function it_returns_casted_value_from_safe_property_access(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['age' => 'required'])
        );

        $safe = $this->request->safe();
        $this->assertInstanceOf(ValidatedInput::class, $safe);
        $this->assertSame(25, $safe->age); // @phpstan-ignore property.notFound (ValidatedInput uses __get)
    }

    #[Test]
    public function it_returns_casted_nested_structure_from_validated(): void
    {
        $this->request->mergeCasts([
            'filters' => Nested::make([
                'price.min' => 'integer',
                'price.max' => 'integer',
            ]),
        ])->merge([
            'filters' => [
                'price' => ['min' => '100', 'max' => '500'],
            ],
        ]);

        $this->request->setValidator(
            Validator::make($this->request->validationData(), ['filters' => 'required|array'])
        );

        $filters = $this->request->validated('filters');

        $this->assertSame(100, $filters['price']['min']);
        $this->assertSame(500, $filters['price']['max']);
    }
}
