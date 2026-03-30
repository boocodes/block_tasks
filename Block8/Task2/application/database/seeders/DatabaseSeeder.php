<?php

namespace Database\Seeders;

use Final2\App\Models\Comment;
use Final2\App\Models\Project;
use Final2\App\Models\Task;
use Final2\App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();
        Project::factory(2)->create();
        Task::factory(10)->create();
        Comment::factory(20)->create();
    }
}
