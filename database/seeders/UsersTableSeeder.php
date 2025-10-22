<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 10 test users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "User $i",
                'username' => "user$i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'avatar' => null,
                'phone' => '0912345678'.$i,
                'bio' => "This is the bio of User $i",
            ]);
        }
    }
}
    