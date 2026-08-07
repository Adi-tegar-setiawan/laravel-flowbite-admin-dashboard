<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'Manajer Gudang',
            'email' => 'manager@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'Manajer Gudang',
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'Staff Gudang',
        ]);
    }
}
