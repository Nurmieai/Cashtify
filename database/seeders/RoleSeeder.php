<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Penjual', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Pelanggan', 'guard_name' => 'web']);
    }
}
