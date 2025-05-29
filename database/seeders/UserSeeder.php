<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('Dswd@12345'),
            'is_active' => true,
        ]);

        $user->assignRole('admin');
    }
}
