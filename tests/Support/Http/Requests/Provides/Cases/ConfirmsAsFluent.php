<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsFluent;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsAsFluent
{
    #[Test]
    public function it_can_cast_to_fluent(): void
    {
        $data = ['a' => 'b', 'c' => 'd'];

        $this->request->mergeCasts([
            'fluent' => AsFluent::class,
        ])->merge([
            'fluent' => json_encode($data),
        ]);

        $this->assertInstanceOf(Fluent::class, $this->request->fluent);
        $this->assertSame(data_get($data, 'a'), $this->request->fluent->a);
        $this->assertSame(data_get($data, 'c'), $this->request->fluent->c);
    }
}
