<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsImmutableDatetime
{
    #[Test]
    public function it_can_cast_to_immutable_datetime(): void
    {
        $datetime = now()->micro(0);

        $this->request->mergeCasts([
            'immutable_datetime' => 'immutable_datetime',
        ])->merge([
            'immutable_datetime' => $datetime->toDateTimeString(),
        ]);

        $this->assertInstanceOf(CarbonImmutable::class, $this->request->immutable_datetime);
        $this->assertEquals($datetime, $this->request->immutable_datetime);
    }
}
