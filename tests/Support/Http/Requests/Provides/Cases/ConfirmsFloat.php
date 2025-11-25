<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsFloat
{
    #[Test]
    public function it_can_cast_to_float(): void
    {
        $float = '123.456';

        $this->request->mergeCasts([
            'float' => 'float',
        ])->merge([
            'float' => $float,
        ]);

        $this->assertIsFloat($this->request->float);
        $this->assertSame((float) $float, $this->request->float);
    }
}
