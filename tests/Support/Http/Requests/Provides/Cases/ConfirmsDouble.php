<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsDouble
{
    #[Test]
    public function it_can_cast_to_double(): void
    {
        $double = '123.456';

        $this->request->mergeCasts([
            'double' => 'double',
        ])->merge([
            'double' => $double,
        ]);

        $this->assertIsFloat($this->request->double);
        $this->assertSame((float) $double, $this->request->double);
    }
}
