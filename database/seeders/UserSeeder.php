<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            ['name' => 'Fenty', 'email' => 'fentysolihah928@gmail.com', 'password' => Hash::make('user123'), 'role' => 'user'],
            ['name' => 'Admin', 'email' => 'fentysolihahgitlab@gmail.com', 'password' => Hash::make('admin123'), 'role' => 'admin'],
        ];

        foreach ($user as $data) {
            User::create($data);
        }
    }
}
