<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = [
            [
                'nama' => 'kepala apoteker',
                'username' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'level' => 'superadmin',
                'password' => Hash::make('123456')
            ]
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
