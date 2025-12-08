<?php

namespace Database\Factories;

use App\Enums\DifficultyEnum;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4),
            'completed' => fake()->boolean(30),
            'difficulty' => fake()->randomElement(DifficultyEnum::cases()),
        ];
    }

    public function easy(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => DifficultyEnum::EASY,
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => DifficultyEnum::MEDIUM,
        ]);
    }

    public function hard(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => DifficultyEnum::HARD,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => false,
        ]);
    }
}
