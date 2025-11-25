<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsEncryptedArray
{
    #[Test]
    public function it_can_cast_to_encrypted_array(): void
    {
        $sensitive = ['a', 'b'];

        $this->request->mergeCasts([
            'encrypted_array' => 'encrypted:array',
        ])->merge([
            'encrypted_array' => $sensitive,
        ]);

        $this->assertIsArray($this->request->encrypted_array);
        $this->assertSame($sensitive, $this->request->encrypted_array);
    }

    #[Test]
    public function it_can_cast_to_encrypted_array_from_json(): void
    {
        $sensitive = ['a', 'b'];

        $this->request->mergeCasts([
            'encrypted_array_from_json' => 'encrypted:array',
        ])->merge([
            'encrypted_array_from_json' => json_encode($sensitive),
        ]);

        $this->assertIsArray($this->request->encrypted_array_from_json);
        $this->assertSame($sensitive, $this->request->encrypted_array_from_json);
    }
}
