<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
     
        $data = [
            [
                'first_name' => 'admin',
                'last_name' => 'main',
                'email' => 'admin@gmail.com',
                'password' => "password",
                'user_type' => "admin",
            ],
        ];
    
        foreach ($data as $d) {
            User::create([
                'first_name' => $d['first_name'],
                'last_name' => $d['last_name'],
                'email' => $d['email'],
                'password' => $d['password'],
                'user_type' => $d['user_type'],
            ]);
        }
    }
}
