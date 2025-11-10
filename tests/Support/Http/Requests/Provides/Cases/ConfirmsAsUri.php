<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsUri;
use Illuminate\Support\Uri;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsUri
{
    #[Test]
    public function it_can_cast_to_uri(): void
    {
        $uri = 'https://example.com';

        $this->request->mergeCasts([
            'uri' => AsUri::class,
        ])->merge([
            'uri' => $uri,
        ]);

        $this->assertInstanceOf(Uri::class, $this->request->uri);
        $this->assertSame($uri, (string) $this->request->uri);
    }
}
