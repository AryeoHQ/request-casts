<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsEnumArrayObject;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Enum;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsAsEnumArrayObject
{
    #[Test]
    public function it_can_cast_to_enum_array_object(): void
    {
        $cases = collect(Enum::cases());

        $this->request->mergeCasts([
            'as_enum_array_object' => AsEnumArrayObject::of(Enum::class),
        ])->merge([
            'as_enum_array_object' => $cases->map->value->toArray(),
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_enum_array_object);
        $this->assertContainsOnlyInstancesOf(Enum::class, $this->request->as_enum_array_object);
    }

    #[Test]
    public function it_can_cast_to_enum_array_object_from_json(): void
    {
        $cases = collect(Enum::cases());

        $this->request->mergeCasts([
            'as_enum_array_object_from_json' => AsEnumArrayObject::of(Enum::class),
        ])->merge([
            'as_enum_array_object_from_json' => json_encode($cases->map->value->toArray()),
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_enum_array_object_from_json);
        $this->assertContainsOnlyInstancesOf(Enum::class, $this->request->as_enum_array_object_from_json);
    }
}
