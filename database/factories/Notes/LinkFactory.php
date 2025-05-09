<?php

namespace Database\Factories\Notes;

use App\Models\Notes\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'link' => $this->faker->url,
            'note_id' => NoteFactory::new(),
            'to_note_id' => null,
            'active' => true,
            'is_external' => true,
            'last_check_at' => null,
            'attributes' => [],
            'reasons' => [],
        ];
    }
}