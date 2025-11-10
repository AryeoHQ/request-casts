<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsDecimal
{
    #[Test]
    public function it_can_cast_to_decimal(): void
    {
        $decimal = '123.456';

        $this->request->mergeCasts([
            'decimal' => 'decimal:2',
        ])->merge([
            'decimal' => $decimal,
        ]);

        $this->assertSame('123.46', $this->request->decimal);
    }
}
