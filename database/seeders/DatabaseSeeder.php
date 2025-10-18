<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        $manager = User::firstOrCreate(
            ['email' => 'test@test.com'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );
        $manager->assignRole($managerRole);
    }
}
