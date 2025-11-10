<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsEncryptedCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsEncryptedCollection
{
    #[Test]
    public function it_can_cast_to_as_encrypted_collection(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_encrypted_collection' => AsEncryptedCollection::class,
        ])->merge([
            'as_encrypted_collection' => $data,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_encrypted_collection);
        $this->assertSame($data, $this->request->as_encrypted_collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_as_encrypted_collection_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_encrypted_collection_from_json' => AsEncryptedCollection::class,
        ])->merge([
            'as_encrypted_collection_from_json' => json_encode($data),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_encrypted_collection_from_json);
        $this->assertSame($data, $this->request->as_encrypted_collection_from_json->toArray());
    }
}
