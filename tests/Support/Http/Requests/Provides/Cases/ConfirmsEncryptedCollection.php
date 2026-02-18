<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Users\User;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsEncryptedCollection
{
    #[Test]
    public function it_can_cast_to_encrypted_collection_from_collection(): void
    {
        $sensitive = collect(['a', 'b']);

        $this->request->mergeCasts([
            'encrypted_collection' => 'encrypted:collection',
        ])->merge([
            'encrypted_collection' => $sensitive,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->encrypted_collection);
        $this->assertSame($sensitive->toArray(), $this->request->encrypted_collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_encrypted_collection_from_array(): void
    {
        $sensitive = collect(['a', 'b']);

        $this->request->mergeCasts([
            'encrypted_collection' => 'encrypted:collection',
        ])->merge([
            'encrypted_collection' => $sensitive->toArray(),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->encrypted_collection);
        $this->assertSame($sensitive->toArray(), $this->request->encrypted_collection->toArray());
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

    #[Test]
    public function it_can_cast_to_encrypted_collection_from_model(): void
    {
        $model = User::factory()->make();

        $this->request->mergeCasts([
            'encrypted_collection_from_model' => 'encrypted:collection',
        ])->merge([
            'encrypted_collection_from_model' => $model,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->encrypted_collection_from_model);
        $this->assertSame($model->toArray(), $this->request->encrypted_collection_from_model->toArray());
    }
}
