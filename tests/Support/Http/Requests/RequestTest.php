<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests;

use Tests\Fixtures\Support\Request;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use ConfirmsInputCasting;

    protected Request $request {
        get => $this->request ??= app(Request::class);
    }
}
