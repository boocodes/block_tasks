<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Task>
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
        $taskStatusArrayValues = [];
        $tasksPriorityArrayValues = [];
        foreach (TaskStatus::cases() as $case) {
            $taskStatusArrayValues[] = $case->value;
        };
        foreach (Priority::cases() as $priority) {
            $tasksPriorityArrayValues[] = $priority->value;
        }
        return [
            'project_id' => random_int(1, 2),
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement($taskStatusArrayValues),
            'priority' => $this->faker->randomElement($tasksPriorityArrayValues),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
