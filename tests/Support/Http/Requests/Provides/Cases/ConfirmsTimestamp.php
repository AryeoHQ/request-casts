<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsTimestamp
{
    #[Test]
    public function it_can_cast_to_timestamp(): void
    {
        $date = now();

        $this->request->mergeCasts([
            'timestamp' => 'timestamp',
        ])->merge([
            'timestamp' => $date->toDateTimeString(),
        ]);

        $this->assertIsInt($this->request->timestamp);
        $this->assertEquals($date->timestamp, $this->request->timestamp);
    }
}
