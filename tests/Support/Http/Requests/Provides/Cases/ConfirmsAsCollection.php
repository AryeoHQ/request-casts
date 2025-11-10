<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsCollection
{
    #[Test]
    public function it_can_cast_to_as_collection(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_collection' => AsCollection::class,
        ])->merge([
            'as_collection' => $data,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_collection);
        $this->assertSame($data, $this->request->as_collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_as_collection_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_collection_from_json' => AsCollection::class,
        ])->merge([
            'as_collection_from_json' => json_encode($data),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_collection_from_json);
        $this->assertSame($data, $this->request->as_collection_from_json->toArray());
    }
}
