<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            ['title' => 'Fix login bug',       'due_date' => now()->format('Y-m-d'),                'priority' => 'high',   'status' => 'pending'],
            ['title' => 'Write unit tests',    'due_date' => now()->addDays(1)->format('Y-m-d'),    'priority' => 'medium', 'status' => 'in_progress'],
            ['title' => 'Update README',       'due_date' => now()->addDays(2)->format('Y-m-d'),    'priority' => 'low',    'status' => 'done'],
            ['title' => 'Deploy to staging',   'due_date' => now()->format('Y-m-d'),                'priority' => 'high',   'status' => 'in_progress'],
            ['title' => 'Code review',         'due_date' => now()->addDays(3)->format('Y-m-d'),    'priority' => 'medium', 'status' => 'pending'],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}