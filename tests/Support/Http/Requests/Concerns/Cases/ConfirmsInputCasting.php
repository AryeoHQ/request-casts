<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Concerns\Cases;

use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Users\User;
use Tests\Support\Http\Requests\Provides;

trait ConfirmsInputCasting
{
    use Provides\Cases\ConfirmsArray;
    use Provides\Cases\ConfirmsAsArrayObject;
    use Provides\Cases\ConfirmsAsCollection;
    use Provides\Cases\ConfirmsAsEncryptedArrayObject;
    use Provides\Cases\ConfirmsAsEncryptedCollection;
    use Provides\Cases\ConfirmsAsEnumArrayObject;
    use Provides\Cases\ConfirmsAsEnumCollection;
    use Provides\Cases\ConfirmsAsFluent;
    use Provides\Cases\ConfirmsAsHtmlString;
    use Provides\Cases\ConfirmsAsStringable;
    use Provides\Cases\ConfirmsAsUri;
    use Provides\Cases\ConfirmsBoolean;
    use Provides\Cases\ConfirmsCollection;
    use Provides\Cases\ConfirmsDate;
    use Provides\Cases\ConfirmsDatetime;
    use Provides\Cases\ConfirmsDecimal;
    use Provides\Cases\ConfirmsDouble;
    use Provides\Cases\ConfirmsEncrypted;
    use Provides\Cases\ConfirmsEncryptedArray;
    use Provides\Cases\ConfirmsEncryptedCollection;
    use Provides\Cases\ConfirmsEnum;
    use Provides\Cases\ConfirmsFloat;
    use Provides\Cases\ConfirmsHashed;
    use Provides\Cases\ConfirmsImmutableDate;
    use Provides\Cases\ConfirmsImmutableDatetime;
    use Provides\Cases\ConfirmsInteger;
    use Provides\Cases\ConfirmsObject;
    use Provides\Cases\ConfirmsReal;
    use Provides\Cases\ConfirmsString;
    use Provides\Cases\ConfirmsTimestamp;

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

    #[Test]
    public function it_leaves_non_casted_values_untouched(): void
    {
        $this->request->merge(['user' => User::factory()->make()]);

        $this->assertInstanceOf(User::class, $this->request->user);
    }
}
