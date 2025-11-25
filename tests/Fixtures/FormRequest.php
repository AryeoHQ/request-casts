<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use AllowDynamicProperties;
use Illuminate\Support\Fluent;
use Support\Http\Requests\Contracts\CastableData;
use Support\Http\Requests\Provides\CastsData;

/**
 * @property Fluent<string, string> $fluent
 */
#[AllowDynamicProperties]
class FormRequest extends \Illuminate\Foundation\Http\FormRequest implements CastableData
{
    use CastsData;

    /**
     * @return array<array-key, string>
     */
    public function casts(): array
    {
        return [
            'one' => 'integer',
        ];
    }
}
