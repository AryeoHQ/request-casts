<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsObject
{
    #[Test]
    public function it_can_cast_to_object(): void
    {
        $data = ['a' => 'b'];

        $this->request->mergeCasts([
            'object' => 'object',
        ])->merge([
            'object' => $data,
        ]);

        $this->assertIsObject($this->request->object);
        $this->assertSame(data_get($data, 'a'), $this->request->object->a);
    }

    #[Test]
    public function it_can_cast_to_object_from_json(): void
    {
        $data = ['a' => 'b'];

        $this->request->mergeCasts([
            'object_from_json' => 'object',
        ])->merge([
            'object_from_json' => json_encode($data),
        ]);

        $this->assertIsObject($this->request->object_from_json);
        $this->assertSame(data_get($data, 'a'), $this->request->object_from_json->a);
    }
}
