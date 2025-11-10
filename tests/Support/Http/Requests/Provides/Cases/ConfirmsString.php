<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsString
{
    #[Test]
    public function it_can_cast_to_string(): void
    {
        $string = 123;

        $this->request->mergeCasts([
            'string' => 'string',
        ])->merge([
            'string' => $string,
        ]);

        $this->assertIsString($this->request->string);
        $this->assertSame((string) $string, $this->request->string);
    }
}
