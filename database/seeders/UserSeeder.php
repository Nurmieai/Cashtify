<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        //  ROLES
        // ============================
        $penjualRole = Role::firstOrCreate(['name' => 'Penjual']);
        $pembeliRole = Role::firstOrCreate(['name' => 'Pembeli']);


        // ============================
        //  ADMIN / PENJUAL UTAMA
        // ============================
        $admin = User::firstOrCreate(
            ['email' => 'admin@mine.com'],
            [
                'name'          => 'Penjual Utama',
                'password'      => Hash::make('12345'),
                'usr_card_url'  => 'assets/images/header/hero-image.jpg',
                'usr_sys_note'  => 'Penjual / Admin utama sistem kasir',
            ]
        );
        $admin->assignRole($penjualRole);


        // ============================
        //  PEMBELI UTAMA
        // ============================
        $mainBuyer = User::firstOrCreate(
            ['email' => 'sofia@mine.com'],
            [
                'name'          => 'Sofia',
                'password'      => Hash::make('12345'),
                'usr_card_url'  => 'assets/images/header/hero-image.jpg',
                'usr_sys_note'  => 'User pembeli standar',
            ]
        );
        $mainBuyer->assignRole($pembeliRole);


        // ============================
        //  USER DUMMY MANUAL (TANPA LOOP)
        // ============================

        $dummyUsers = [
            [
                'email' => 'dummy1@mine.com',
                'name'  => 'Dion Septian Kevin',
            ],
            [
                'email' => 'dummy2@mine.com',
                'name'  => 'Aiman Fairus',
            ],
            [
                'email' => 'dummy3@mine.com',
                'name'  => 'Amelia Putri Saparani',
            ],
            [
                'email' => 'dummy4@mine.com',
                'name'  => 'Andi Juliansyah',
            ],
            [
                'email' => 'dummy5@mine.com',
                'name'  => 'Abror Fadillah Ramadhan',
            ],
            [
                'email' => 'dummy6@mine.com',
                'name'  => 'Dian Hakim',
            ],
            [
                'email' => 'dummy7@mine.com',
                'name'  => 'Raditia Scorpio Djayakusumah',
            ],
            [
                'email' => 'dummy8@mine.com',
                'name'  => 'Reyzal',
            ],
            [
                'email' => 'dummy9@mine.com',
                'name'  => 'Savaira Malika Fitri Handini',
            ],
        ];

        foreach ($dummyUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'          => $data['name'],
                    'password'      => Hash::make('12345'),
                    'usr_card_url'  => 'assets/images/header/hero-image.jpg',
                    'usr_sys_note'  => 'Akun dummy untuk testing',
                ]
            );

            $user->assignRole($pembeliRole);
        }
    }
}
