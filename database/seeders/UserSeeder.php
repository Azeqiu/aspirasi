<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('admin123&'),
            'role'     => 'admin',
        ]);

        // Siswa contoh
        User::create([
            'name'     => 'Siswa Test',
            'username' => 'siswa1',
            'email'    => 'siswa1@example.com',
            'password' => Hash::make('siswa123'),
            'role'     => 'siswa',
        ]);
    }
}