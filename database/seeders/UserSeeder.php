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
        // ROLE
        $penjualRole = Role::firstOrCreate(['name' => 'Penjual', 'guard_name' => 'web']);
        $pembeliRole = Role::firstOrCreate(['name' => 'Pembeli', 'guard_name' => 'web']);

        // TEMPLATE SALDO
        $defaultWalletJson = json_encode([
            'saldo' => [
                'bca'  => 150000,
                'dana' => 750000,
            ],
            'note' => 'Akun dengan saldo dummy untuk pengujian pembayaran',
        ]);

        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@mine.com'],
            [
                'name'         => 'Penjual Utama',
                'password'     => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/user_placeholder.jpg',
                'usr_sys_note' => $defaultWalletJson,
            ]
        );
        $admin->assignRole($penjualRole);

        // PEMBELI UTAMA
        $mainBuyer = User::firstOrCreate(
            ['email' => 'sofia@mine.com'],
            [
                'name'         => 'Sofia',
                'password'     => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/user_placeholder.jpg',
                'usr_sys_note' => $defaultWalletJson,
            ]
        );
        $mainBuyer->assignRole($pembeliRole);

        // DUMMY USERS
        $dummyUsers = [
            ['email' => 'dummy1@mine.com', 'name' => 'Dion Septian Kevin'],
            ['email' => 'dummy2@mine.com', 'name' => 'Aiman Fairus'],
            ['email' => 'dummy3@mine.com', 'name' => 'Amelia Putri Saparani'],
            ['email' => 'dummy4@mine.com', 'name' => 'Andi Juliansyah'],
            ['email' => 'dummy5@mine.com', 'name' => 'Abror Fadillah Ramadhan'],
            ['email' => 'dummy6@mine.com', 'name' => 'Dian Hakim'],
            ['email' => 'dummy7@mine.com', 'name' => 'Raditia Scorpio Djayakusumah'],
            ['email' => 'dummy8@mine.com', 'name' => 'Reyzal'],
            ['email' => 'dummy9@mine.com', 'name' => 'Savaira Malika Fitri Handini'],
        ];

        foreach ($dummyUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'         => $data['name'],
                    'password'     => Hash::make('12345'),
                    'usr_card_url' => 'assets/images/header/user_placeholder.jpg',
                    'usr_sys_note' => $defaultWalletJson,
                ]
            );

            $user->assignRole($pembeliRole);
        }
    }
}
