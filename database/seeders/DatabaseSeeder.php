<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::updateOrCreate(
            [
                'email' => 'admin@test.com',
            ],
            [
                'name'     => 'Admin Test',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        User::factory()->count(50)->create();
        $this->call(ActivitiesSeeder::class);
    }
}