<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsHashed
{
    #[Test]
    public function it_can_cast_to_hashed(): void
    {
        $password = 'password';

        $this->request->mergeCasts([
            'hashed' => 'hashed',
        ])->merge([
            'hashed' => $password,
        ]);

        $this->assertNotSame($password, $this->request->hashed);
    }
}
