<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
     use WithoutModelEvents;
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '01000000000',
            'password' => Hash::make('password'), // كلمة مرور ثابتة
            'status' => UserStatus::ACTIVE->value,
            'address' => 'Admin Address',
        ]);
        User::factory()->count(100)->create();
    }
}

