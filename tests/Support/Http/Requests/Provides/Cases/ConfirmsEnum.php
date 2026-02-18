<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Enum;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsEnum
{
    #[Test]
    public function it_can_cast_to_enum(): void
    {
        $enum = Enum::Draft;

        $this->request->mergeCasts([
            'enum' => Enum::class,
        ])->merge([
            'enum' => $enum->value,
        ]);

        $this->assertSame($enum, $this->request->enum);
    }
}
