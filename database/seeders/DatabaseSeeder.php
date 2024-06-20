<?php

namespace Database\Seeders;

use App\Models\Column;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createRoles();
        $this->createUsers();
        $this->createProjects();
        $this->createColumns();
        $this->createTasks();
    }

    private function createRoles(): void
    {
        foreach (Role::ROLES_LIST as $roleName) {
            Role::factory()->create(['name' => $roleName]);
        }
    }

    private function createUsers(): void
    {
        User::factory()->create(['name' => 'admin', 'email' => "admin@mail.ru"])
            ->roles()
            ->attach(Role::ADMIN_ROLE()->id);

        Role::where('name', '!=', Role::ROLES_LIST['ADMIN'])
            ->each(function (Role $role) {
                User::factory()
                    ->create(['name' => 'default_' . $role->name, 'email' => "$role->name@mail.ru"])
                    ->roles()
                    ->attach($role->id);
            });

        User::factory(44)->create()
            ->each(function (User $user) {
                $role = Role::where('name', '!=', ROLE::ROLES_LIST['ADMIN'])->inRandomOrder()->first();
                $user->roles()->attach($role->id);
            });

    }

    private function createProjects(): void
    {
        Project::factory()->create(['name' => 'default_project']);
        Project::factory(2)->create();

        Project::all()->each(function (Project $project) {
            Role::FULL_ACCESS_ROLES()->each(function (Role $role) use ($project) {
                $project->users()->attach($role->users()->get()->pluck('id'));
            });
        });
    }

    private function createColumns(): void
    {
        Column::factory(150)->create();
    }

    private function createTasks(): void
    {
        Task::factory(500)->create();
    }
}
