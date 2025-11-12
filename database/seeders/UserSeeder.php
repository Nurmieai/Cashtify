<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        user::firstOrCreate(
            ['usr_id' => 1],
            [
                'name' => 'Nabil',
                'email' => 'nabil@mine.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'Admin utama',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );

        user::firstOrCreate(
            ['usr_id' => 2],
            [
                'name' => 'Sofia Cantik',
                'email' => 'sofia@mine.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'User percobaan',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );
    }
}
