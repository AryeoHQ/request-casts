<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Casts\Nested;

/**
 * @mixin \Tests\Support\Http\Requests\FormRequestTest
 */
trait ConfirmsValidationData
{
    #[Test]
    public function it_validates_against_raw_input_not_casted_values(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $data = $this->request->validationData();

        $this->assertSame('25', $data['age']);

        $validator = Validator::make($data, ['age' => 'string']);

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_fails_validation_on_genuinely_invalid_raw_input(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => 'abc',
        ]);

        $data = $this->request->validationData();

        $validator = Validator::make($data, ['age' => 'integer']);

        $this->assertTrue($validator->fails());
    }

    #[Test]
    public function it_provides_casted_values_after_validation_passes(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $data = $this->request->validationData();

        $validator = Validator::make($data, ['age' => 'required']);
        $this->assertTrue($validator->passes());

        $this->assertSame(25, $this->request->age);
    }

    #[Test]
    public function it_does_not_leak_nested_casts_into_validation_data(): void
    {
        $this->request->mergeCasts([
            'filters' => Nested::make([
                'price.min' => 'integer',
            ]),
        ])->merge([
            'filters' => [
                'price' => ['min' => '100'],
            ],
        ]);

        $data = $this->request->validationData();

        $this->assertSame('100', $data['filters']['price']['min']);

        $validator = Validator::make($data, ['filters.price.min' => 'string']);

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_returns_casted_values_from_all(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $all = $this->request->all();

        $this->assertSame(25, $all['age']);
    }

    #[Test]
    public function it_returns_casted_values_from_property_access(): void
    {
        $this->request->mergeCasts([
            'age' => 'integer',
        ])->merge([
            'age' => '25',
        ]);

        $this->assertSame(25, $this->request->age);
    }
}
