<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsHtmlString;
use Illuminate\Support\HtmlString;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Http\Requests\Provides\CastsInputTest;

/**
 * @mixin CastsInputTest
 */
trait ConfirmsAsHtmlString
{
    #[Test]
    public function it_can_cast_to_html_string(): void
    {
        $html = '<div>abc</div>';

        $this->request->mergeCasts([
            'html' => AsHtmlString::class,
        ])->merge([
            'html' => $html,
        ]);

        $this->assertInstanceOf(HtmlString::class, $this->request->html);
        $this->assertSame($html, $this->request->html->toHtml());
    }
}
