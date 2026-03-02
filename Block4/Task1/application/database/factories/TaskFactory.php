<?php

namespace Database\Factories;

use App\Enums\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskStatusArray = [];
        foreach (Task::cases() as $case) {
            $taskStatusArray[] = $case->value;
        }
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($taskStatusArray)
        ];
    }
}
