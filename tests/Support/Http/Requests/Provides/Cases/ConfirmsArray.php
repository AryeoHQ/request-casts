<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsArray
{
    #[Test]
    public function it_can_cast_to_array(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'array' => 'array',
        ])->merge([
            'array' => $data,
        ]);

        $this->assertSame($data, $this->request->array);
    }

    #[Test]
    public function it_can_cast_to_array_from_json(): void
    {
        $data = ['a', 'b'];

        $this->request->mergeCasts([
            'array' => 'array',
        ])->merge([
            'array' => json_encode($data),
        ]);

        $this->assertSame($data, $this->request->array);
    }
}
