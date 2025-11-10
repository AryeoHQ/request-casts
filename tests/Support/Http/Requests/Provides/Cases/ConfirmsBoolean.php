<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsBoolean
{
    #[Test]
    public function it_can_cast_to_boolean_from_string(): void
    {
        $this->request->mergeCasts([
            'true' => 'boolean',
            'false' => 'boolean',
        ])->merge([
            'true' => '1',
            'false' => '0',
        ]);

        $this->assertIsBool($this->request->true);
        $this->assertTrue($this->request->true);

        $this->assertIsBool($this->request->false);
        $this->assertFalse($this->request->false);
    }

    #[Test]
    public function it_can_cast_to_boolean_from_integer(): void
    {
        $this->request->mergeCasts([
            'true' => 'boolean',
            'false' => 'boolean',
        ])->merge([
            'true' => 1,
            'false' => 0,
        ]);

        $this->assertIsBool($this->request->true);
        $this->assertTrue($this->request->true);

        $this->assertIsBool($this->request->false);
        $this->assertFalse($this->request->false);
    }
}
