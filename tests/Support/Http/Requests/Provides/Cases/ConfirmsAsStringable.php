<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Support\Stringable;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsAsStringable
{
    #[Test]
    public function it_can_cast_to_stringable(): void
    {
        $string = 'abc';

        $this->request->mergeCasts([
            'stringable' => AsStringable::class,
        ])->merge([
            'stringable' => $string,
        ]);

        $this->assertInstanceOf(Stringable::class, $this->request->stringable);
    }
}
