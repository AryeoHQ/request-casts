<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides;

use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Request;
use Tests\TestCase;

class CastsInputTest extends TestCase
{
    use Cases\ConfirmsArray;
    use Cases\ConfirmsAsArrayObject;
    use Cases\ConfirmsAsCollection;
    use Cases\ConfirmsAsEncryptedArrayObject;
    use Cases\ConfirmsAsEncryptedCollection;
    use Cases\ConfirmsAsEnumArrayObject;
    use Cases\ConfirmsAsEnumCollection;
    use Cases\ConfirmsAsFluent;
    use Cases\ConfirmsAsHtmlString;
    use Cases\ConfirmsAsStringable;
    use Cases\ConfirmsAsUri;
    use Cases\ConfirmsBoolean;
    use Cases\ConfirmsCollection;
    use Cases\ConfirmsDate;
    use Cases\ConfirmsDatetime;
    use Cases\ConfirmsDecimal;
    use Cases\ConfirmsDouble;
    use Cases\ConfirmsEncrypted;
    use Cases\ConfirmsEncryptedArray;
    use Cases\ConfirmsEncryptedCollection;
    use Cases\ConfirmsEnum;
    use Cases\ConfirmsFloat;
    use Cases\ConfirmsHashed;
    use Cases\ConfirmsImmutableDate;
    use Cases\ConfirmsImmutableDatetime;
    use Cases\ConfirmsInteger;
    use Cases\ConfirmsObject;
    use Cases\ConfirmsReal;
    use Cases\ConfirmsString;
    use Cases\ConfirmsTimestamp;

    protected Request $request {
        get => $this->request ??= new Request;
    }

    #[Test]
    public function it_can_define_casts_in_method(): void
    {
        $this->assertSame(['one' => 'integer'], $this->request->casts());
    }

    #[Test]
    public function it_can_merge_casts(): void
    {
        $this->request->mergeCasts(['two' => 'boolean']);

        $this->assertSame(
            ['one' => 'integer', 'two' => 'boolean'],
            $this->request->casts
        );
    }
}
