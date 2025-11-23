<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('users')) {
            $this->command->error('Tabel users tidak ditemukan. Jalankan migrasi terlebih dahulu.');
            return;
        }

        // ===== ROLES =====
        $penjualRole = Role::firstOrCreate(['name' => 'Penjual', 'guard_name' => 'web']);
        $pembeliRole = Role::firstOrCreate(['name' => 'Pembeli', 'guard_name' => 'web']);

        // ===== USER DEMO =====
        $penjual = User::firstOrCreate(
            ['usr_id' => 1],
            [
                'name' => 'Penjual Demo',
                'email' => 'penjual@demo.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'Penjual / Admin utama (Seeder)',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );
        $penjual->assignRole($penjualRole);

        $pembeli = User::firstOrCreate(
            ['usr_id' => 2],
            [
                'name' => 'Pembeli Demo',
                'email' => 'pembeli@demo.com',
                'password' => Hash::make('12345'),
                'usr_card_url' => 'assets/images/header/hero-image.jpg',
                'usr_sys_note' => 'Pembeli percobaan (Seeder)',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ]
        );
        $pembeli->assignRole($pembeliRole);

        $penjualId = $penjual->usr_id;
        $pembeliId = $pembeli->usr_id;

        // ===== PANGGIL PRODUCT SEEDER DI SINI =====
        $this->call(ProductSeeder::class);

        // ===== TRANSAKSI DEMO =====
        $buyers = [];

        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "buyer$i@demo.com"],
                [
                    'name' => "Pembeli $i",
                    'password' => Hash::make('12345'),
                    'usr_card_url' => 'assets/images/header/hero-image.jpg',
                    'usr_sys_note' => 'Pembeli dummy (Seeder)',
                    'usr_created_at' => now(),
                    'usr_updated_at' => now(),
                ]
            );
            $user->assignRole($pembeliRole);
            $buyers[] = $user->usr_id;
        }

        $paymentMethods = ['dana', 'bank_transfer'];

        for ($i = 1; $i <= 15; $i++) {
            $buyerId = $buyers[array_rand($buyers)];
            $payment = $paymentMethods[array_rand($paymentMethods)];

            $subtotal = rand(20000, 200000);
            $invoice = 'RRQ-' . date('Ymd') . '-' . Str::upper(Str::random(6));

            DB::table('transactions')->insert([
                'tst_invoice' => $invoice,
                'tst_buyer_id' => $buyerId,
                'tst_seller_id' => $penjualId,
                'tst_total' => $subtotal,
                'tst_subtotal' => $subtotal,
                'tst_shipping_cost' => 0,
                'tst_payment_method' => $payment,
                'tst_payment_status' => '1',    // WAJIB STR, bukan int
                'tst_status' => '1',            // WAJIB STR
                'tst_notes' => "Dummy transaksi ke-$i",
                'tst_created_by' => $penjualId,
                'tst_updated_by' => $penjualId,
                'tst_created_at' => now()->subDays(rand(1, 60)),
                'tst_updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }


        // ===== ACCOUNTING DEMO =====
        DB::table('accountings')->insert([
            'act_user_id' => $penjualId,
            'act_exel_url' => '',
            'act_period_from' => now()->startOfMonth()->toDateString(),
            'act_period_to' => now()->endOfMonth()->toDateString(),
            'act_total_sales' => $subtotal,
            'act_total_items_sold' => 2,
            'act_created_by' => $penjualId,
            'act_updated_by' => $penjualId,
            'act_created_at' => now(),
            'act_updated_at' => now(),
        ]);
    }
}
