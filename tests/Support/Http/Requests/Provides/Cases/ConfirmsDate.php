<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsDate
{
    #[Test]
    public function it_can_cast_to_date(): void
    {
        $date = now()->micro(0);

        $this->request->mergeCasts([
            'date' => 'date',
        ])->merge([
            'date' => $date->toDateTimeString(),
        ]);

        $this->assertInstanceOf(Carbon::class, $this->request->date);
        $this->assertNotEquals($date, $this->request->date);
    }
}
