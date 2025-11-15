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
        $penjualRole = Role::firstOrCreate([
            'name' => 'Penjual',
        ]);

        $pembeliRole = Role::firstOrCreate([
            'name' => 'Pembeli',
        ]);

        $penjual = User::firstOrCreate(
            ['usr_id' => 1], // PK kamu custom
            [
                'name' => 'Penjual Utama',
                'email' => 'admin@mine.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'Penjual / Admin utama sistem kasir',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );
        $penjual->assignRole($penjualRole);

        $pembeli = User::firstOrCreate(
            ['usr_id' => 2],
            [
                'name' => 'Sofia',
                'email' => 'pembeli@mine.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'User pembeli standar',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );
        $pembeli->assignRole($pembeliRole);
    }
}
