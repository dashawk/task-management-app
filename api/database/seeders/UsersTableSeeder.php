<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Demo User 1', 'email' => 'user1@example.com', 'password' => 'password'],
            ['name' => 'Demo User 2', 'email' => 'user2@example.com', 'password' => 'password'],
            ['name' => 'Demo User 3', 'email' => 'user3@example.com', 'password' => 'password'],
            ['name' => 'Demo User 4', 'email' => 'user4@example.com', 'password' => 'password'],
            ['name' => 'Demo User 5', 'email' => 'user5@example.com', 'password' => 'password'],
        ];

        foreach ($users as $data) {
            // Model casts will hash the password; ensure email is verified for login flows
            User::factory()->create(array_merge($data, [
                'email_verified_at' => now(),
            ]));
        }
    }
}
