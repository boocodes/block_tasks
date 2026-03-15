<?php

namespace Database\Seeders;

use Task3\App\Models\Task;
use Task3\App\Models\User;
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
        Task::factory(10)->create();
    }
}
