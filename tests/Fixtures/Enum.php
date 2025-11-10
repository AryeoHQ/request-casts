<?php

declare(strict_types=1);

namespace Tests\Fixtures;

enum Enum: string
{
    case Draft = 'draft';
    case Published = 'published';
}
