<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'usr_id' => 1,
                'name' => 'Nabil Hakim',
                'email' => 'nabil@example.com',
                'password' => Hash::make('password123'),
                'usr_card_url' => '/logo/user_placeholder.jpg',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
                'usr_sys_note' => 'Admin utama',
            ],
            [
                'usr_id' => 2,
                'name' => 'Sofia Cantik',
                'email' => 'sofia@example.com',
                'password' => Hash::make('12345678'),
                'usr_card_url' => '/logo/user_placeholder.jpg',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
                'usr_sys_note' => 'User percobaan',
            ],
        ]);
    }
}
