<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsEncrypted
{
    #[Test]
    public function it_can_cast_to_encrypted(): void
    {
        $sensitive = 'password';

        $this->request->mergeCasts([
            'encrypted' => 'encrypted',
        ])->merge([
            'encrypted' => $sensitive,
        ]);

        $this->assertSame($sensitive, $this->request->encrypted);
    }
}
