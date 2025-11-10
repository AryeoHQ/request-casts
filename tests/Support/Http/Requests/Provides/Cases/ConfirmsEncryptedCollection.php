<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsEncryptedCollection
{
    #[Test]
    public function it_can_cast_to_encrypted_collection(): void
    {
        $sensitive = ['a', 'b'];

        $this->request->mergeCasts([
            'encrypted_collection' => 'encrypted:collection',
        ])->merge([
            'encrypted_collection' => $sensitive,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->encrypted_collection);
        $this->assertSame($sensitive, $this->request->encrypted_collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_encrypted_collection_from_json(): void
    {
        $sensitive = ['a', 'b'];

        $this->request->mergeCasts([
            'encrypted_collection_from_json' => 'encrypted:collection',
        ])->merge([
            'encrypted_collection_from_json' => json_encode($sensitive),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->encrypted_collection_from_json);
        $this->assertSame($sensitive, $this->request->encrypted_collection_from_json->toArray());
    }
}
