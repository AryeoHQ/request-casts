<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsDatetime
{
    #[Test]
    public function it_can_cast_to_datetime(): void
    {
        $datetime = now()->micro(0);

        $this->request->mergeCasts([
            'datetime' => 'datetime',
        ])->merge([
            'datetime' => $datetime->toDateTimeString(),
        ]);

        $this->assertInstanceOf(Carbon::class, $this->request->datetime);
        $this->assertEquals($datetime, $this->request->datetime);
    }
}
