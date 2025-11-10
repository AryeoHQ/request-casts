<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsImmutableDate
{
    #[Test]
    public function it_can_cast_to_immutable_date(): void
    {
        $date = now()->micro(0);

        $this->request->mergeCasts([
            'immutable_date' => 'immutable_date',
        ])->merge([
            'immutable_date' => $date->toDateTimeString(),
        ]);

        $this->assertInstanceOf(CarbonImmutable::class, $this->request->immutable_date);
        $this->assertNotEquals($date, $this->request->immutable_date);
    }
}
