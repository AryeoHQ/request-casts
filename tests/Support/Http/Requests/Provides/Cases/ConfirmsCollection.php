<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsCollection
{
    #[Test]
    public function it_can_cast_to_collection(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'collection' => 'collection',
        ])->merge([
            'collection' => $data,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->collection);
        $this->assertSame($data, $this->request->collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_collection_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'collection_from_json' => 'collection',
        ])->merge([
            'collection_from_json' => json_encode($data),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->collection_from_json);
        $this->assertSame($data, $this->request->collection_from_json->toArray());
    }
}
