<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Activities;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('No users found.');
            return;
        }

        $actions = ['login', 'view_page', 'submit_form'];

        $data = [];

        for ($i = 0; $i < 10000; $i++) {
            $data[] = [
                'user_id' => $userIds[array_rand($userIds)],
                'action' => $actions[array_rand($actions)],
                'created_at' => now()->subDays(rand(0, 30)),
            ];
        }

        DB::table('activities')->insert($data);
    }
}