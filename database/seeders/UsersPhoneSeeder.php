<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersPhoneSeeder extends Seeder
{
    public function run(): void
    {
        // Example: Add dummy phone numbers for existing users
        User::chunk(50, function ($users) {
            foreach ($users as $user) {
                if (!$user->phone_number) {
                    // Assign a placeholder number or fetch from somewhere safe
                    $user->phone_number = '+639100000000'; // Replace with real number if you have
                    $user->save();
                }
            }
        });
    }
}
