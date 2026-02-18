<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsEncryptedCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Users\User;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsAsEncryptedCollection
{
    #[Test]
    public function it_can_cast_to_as_encrypted_collection_from_collection(): void
    {
        $data = collect(['a', 'b']);

        $this->request->mergeCasts([
            'as_encrypted_collection_from_collection' => AsEncryptedCollection::class,
        ])->merge([
            'as_encrypted_collection_from_collection' => $data,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_encrypted_collection_from_collection);
        $this->assertSame($data->toArray(), $this->request->as_encrypted_collection_from_collection->toArray());
    }

    #[Test]
    public function it_can_cast_to_as_encrypted_collection_from_array(): void
    {
        $data = collect(['a', 'b']);

        $this->request->mergeCasts([
            'as_encrypted_collection_from_array' => AsEncryptedCollection::class,
        ])->merge([
            'as_encrypted_collection_from_array' => $data->toArray(),
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_encrypted_collection_from_array);
        $this->assertSame($data->toArray(), $this->request->as_encrypted_collection_from_array->toArray());
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

    #[Test]
    public function it_can_cast_to_as_encrypted_collection_from_model(): void
    {
        $model = User::factory()->make();

        $this->request->mergeCasts([
            'as_encrypted_collection_from_model' => AsEncryptedCollection::class,
        ])->merge([
            'as_encrypted_collection_from_model' => $model,
        ]);

        $this->assertInstanceOf(Collection::class, $this->request->as_encrypted_collection_from_model);
        $this->assertSame($model->toArray(), $this->request->as_encrypted_collection_from_model->toArray());
    }
}
