<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Penjual', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Pembeli', 'guard_name' => 'web']);
    }
}

