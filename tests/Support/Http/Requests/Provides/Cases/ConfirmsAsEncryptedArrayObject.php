<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsEncryptedArrayObject
{
    #[Test]
    public function it_can_cast_to_encrypted_array_object(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_encrypted_array_object' => AsEncryptedArrayObject::class,
        ])->merge([
            'as_encrypted_array_object' => $data,
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_encrypted_array_object);
    }

    #[Test]
    public function it_can_cast_to_encrypted_array_object_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'as_encrypted_array_object_from_json' => AsEncryptedArrayObject::class,
        ])->merge([
            'as_encrypted_array_object_from_json' => json_encode($data),
        ]);

        $this->assertInstanceOf(ArrayObject::class, $this->request->as_encrypted_array_object_from_json);
    }
}
