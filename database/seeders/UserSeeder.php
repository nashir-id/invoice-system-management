<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner - akses penuh
        User::create([
            'name'      => 'M. Nashirudin Rabbani',
            'email'     => 'owner@gmail.com',
            'password'  => Hash::make('12345678'),
            'role'      => 'owner',
            'is_active' => true,
        ]);

        // Admin - staff senior
        User::create([
            'name'      => 'Admin NASHIR.ID',
            'email'     => 'adminnashir@gmail.com',
            'password'  => Hash::make('123456789'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Staff - read only
        // User::create([
        //     'name'      => 'Staff NASHIR.ID',
        //     'email'     => 'staff@nashir.id',
        //     'password'  => Hash::make('1234567893'),
        //     'role'      => 'staff',
        //     'is_active' => true,
        // ]);
    }
}