<?php

declare(strict_types=1);

namespace Tests\Fixtures\Users;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UseFactory(Factory::class)]
class User extends \Illuminate\Database\Eloquent\Model
{
    /** @use HasFactory<Factory> */
    use HasFactory;
}
