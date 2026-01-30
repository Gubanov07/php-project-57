<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * @return void
     */
    public function run(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            User::firstOrCreate(
                ['email' => 'user' . $i . '@example.com'],
                [
                'name' => 'User ' . $i,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                ]
            );
        }

        $this->call([
            TaskStatusSeeder::class,
            LabelSeeder::class,
            TaskSeeder::class
        ]);

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
