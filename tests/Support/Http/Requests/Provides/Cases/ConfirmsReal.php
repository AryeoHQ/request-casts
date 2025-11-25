<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsReal
{
    #[Test]
    public function it_can_cast_to_real(): void
    {
        $real = '123.456';

        $this->request->mergeCasts([
            'real' => 'real',
        ])->merge([
            'real' => $real,
        ]);

        $this->assertIsFloat($this->request->real);
        $this->assertSame((float) $real, $this->request->real);
    }
}
