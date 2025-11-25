<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Users\User;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsArray
{
    #[Test]
    public function it_can_cast_to_array(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'array' => 'array',
        ])->merge([
            'array' => $data,
        ]);

        $this->assertSame($data, $this->request->array);
    }

    #[Test]
    public function it_can_cast_to_array_from_collection(): void
    {
        $collection = collect(['a', 'b']);

        $this->request->mergeCasts([
            'array_from_collection' => 'array',
        ])->merge([
            'array_from_collection' => $collection,
        ]);

        $this->assertSame($collection->toArray(), $this->request->array_from_collection);
    }

    #[Test]
    public function it_can_cast_to_array_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'array_from_json' => 'array',
        ])->merge([
            'array_from_json' => json_encode($data),
        ]);

        $this->assertSame($data, $this->request->array_from_json);
    }

    #[Test]
    public function it_can_cast_to_array_from_model(): void
    {
        $model = User::factory()->make();

        $this->request->mergeCasts([
            'array_from_model' => 'array',
        ])->merge([
            'array_from_model' => $model,
        ]);

        $this->assertSame($model->toArray(), $this->request->array_from_model);
    }
}
