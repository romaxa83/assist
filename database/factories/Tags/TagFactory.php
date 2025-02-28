<?php

namespace Database\Factories\Tags;

use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {

        $name = $this->faker->unique()->word();

        return [
            'name' => $name,
            'slug' => slug($name),
            'color' => $this->faker->hexColor,
            'public_attached' => 0,
            'private_attached' => 0,
        ];
    }
}
