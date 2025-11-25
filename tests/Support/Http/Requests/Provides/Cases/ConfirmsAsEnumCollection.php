<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Enum;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsAsEnumCollection
{
    #[Test]
    public function it_can_cast_to_enum_collection_from_array(): void
    {
        $cases = collect(Enum::cases());

        $this->request->mergeCasts([
            'as_enum_collection' => AsEnumCollection::of(Enum::class),
        ])->merge([
            'as_enum_collection' => $cases->map->value->toArray(),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_enum_collection);
        $this->assertContainsOnlyInstancesOf(Enum::class, $this->request->as_enum_collection);
    }

    #[Test]
    public function it_can_cast_to_enum_collection_from_collection(): void
    {
        $cases = collect(Enum::cases());

        $this->request->mergeCasts([
            'as_enum_collection' => AsEnumCollection::of(Enum::class),
        ])->merge([
            'as_enum_collection' => $cases->map->value,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_enum_collection);
        $this->assertContainsOnlyInstancesOf(Enum::class, $this->request->as_enum_collection);
    }

    #[Test]
    public function it_can_cast_to_enum_collection_from_json(): void
    {
        $cases = collect(Enum::cases());

        $this->request->mergeCasts([
            'as_enum_collection_from_json' => AsEnumCollection::of(Enum::class),
        ])->merge([
            'as_enum_collection_from_json' => json_encode($cases->map->value),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_enum_collection_from_json);
        $this->assertContainsOnlyInstancesOf(Enum::class, $this->request->as_enum_collection_from_json);
    }
}
