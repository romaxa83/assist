<?php

namespace Database\Factories\Notes;

use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {

        $name = $this->faker->unique()->sentence();

        return [
            'title' => $name,
            'status' => NoteStatus::Draft(),
            'slug' => slug($name),
            'text' => $this->faker->text,
            'weight' => 0,
            'author_id' => User::factory(),
        ];
    }
}
