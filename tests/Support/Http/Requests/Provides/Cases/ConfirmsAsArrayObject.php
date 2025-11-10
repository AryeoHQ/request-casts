<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsArrayObject
{
    #[Test]
    public function it_can_cast_to_array_object(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_array_object' => AsArrayObject::class,
        ])->merge([
            'as_array_object' => $data,
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_array_object);
    }

    #[Test]
    public function it_can_cast_to_array_object_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_array_object_from_json' => AsArrayObject::class,
        ])->merge([
            'as_array_object_from_json' => json_encode($data),
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_array_object_from_json);
    }
}
