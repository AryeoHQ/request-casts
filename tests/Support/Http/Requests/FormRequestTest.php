<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests;

use Tests\Fixtures\Support\FormRequest;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;
use Tests\TestCase;

class FormRequestTest extends TestCase
{
    use ConfirmsInputCasting;

    protected FormRequest $request {
        get => $this->request ??= app(FormRequest::class);
    }
}
