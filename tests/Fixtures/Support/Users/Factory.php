<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Users;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<User>
 */
class Factory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
