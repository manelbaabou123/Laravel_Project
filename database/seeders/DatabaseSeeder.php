<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $user->roles()->attach(1);
         \App\Models\User::factory(10)->create();
         \App\Models\Project::factory(10)->create([
            'creator_id' => $user->id,
        ]);
        \App\Models\Task::factory(1)->create([
            'creator_id' => $user->id,
        ]);


         Role::create(['name' => 'admin']);
         Role::create(['name' => 'user']);
        foreach (User::all() as $user) {
                $user->roles()->attach(2);
        }

        

        

    }
}
