<?php

namespace Database\Factories;

use App\Models\Column;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $column = Column::inRandomOrder()->first();
        $user = $column->project->users->random();

        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'column_id' => $column->id,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(rand(1,10))
        ];
    }
}
