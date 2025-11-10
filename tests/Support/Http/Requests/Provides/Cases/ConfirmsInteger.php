<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsInteger
{
    #[Test]
    public function it_can_cast_to_integer(): void
    {
        $integer = '123';

        $this->request->mergeCasts([
            'integer' => 'integer',
        ])->merge([
            'integer' => $integer,
        ]);

        $this->assertIsInt($this->request->integer);
        $this->assertSame((int) $integer, $this->request->integer);
    }
}
