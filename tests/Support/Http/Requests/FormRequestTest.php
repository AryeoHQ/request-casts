<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests;

use Tests\Fixtures\Support\FormRequest;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;
use Tests\Support\Http\Requests\Provides\Cases\ConfirmsValidatedOutput;
use Tests\Support\Http\Requests\Provides\Cases\ConfirmsValidationData;
use Tests\TestCase;

class FormRequestTest extends TestCase
{
    use ConfirmsInputCasting;
    use ConfirmsValidatedOutput;
    use ConfirmsValidationData;

    protected FormRequest $request {
        get => $this->request ??= app(FormRequest::class);
    }
}
