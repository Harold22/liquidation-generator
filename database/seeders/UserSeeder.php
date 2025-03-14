<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ public function run(): void
    {
        // Create a default admin user
        User::create([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('dswd12345'),
        ]);
    }
}
