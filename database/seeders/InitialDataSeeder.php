<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
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

        $penjualRole = Role::firstOrCreate(['name' => 'Penjual', 'guard_name' => 'web']);
        $pembeliRole = Role::firstOrCreate(['name' => 'Pembeli', 'guard_name' => 'web']);

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

        $this->command->info("✓ User Penjual: {$penjualId}, Pembeli: {$pembeliId}");

        $products = [
            [
                'prd_name' => 'Kopi Arabika 250g',
                'prd_description' => 'Kopi arabika medium roast.',
                'prd_status' => 'tersedia',
                'prd_price' => 50000,
                'prd_card_url' => null,
                'prd_created_by' => $penjualId,
                'prd_updated_by' => $penjualId,
                'usr_sys_note' => 'Produk demo',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
                'usr_deleted_at' => null,
            ],
            [
                'prd_name' => 'Roti Coklat',
                'prd_description' => 'Roti isi coklat.',
                'prd_status' => 'tersedia',
                'prd_price' => 15000,
                'prd_card_url' => null,
                'prd_created_by' => $penjualId,
                'prd_updated_by' => $penjualId,
                'usr_sys_note' => 'Produk demo',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
                'usr_deleted_at' => null,
            ],
            [
                'prd_name' => 'Air Mineral 600ml',
                'prd_description' => 'Air mineral kemasan 600ml.',
                'prd_status' => 'tersedia',
                'prd_price' => 5000,
                'prd_card_url' => null,
                'prd_created_by' => $penjualId,
                'prd_updated_by' => $penjualId,
                'usr_sys_note' => 'Produk demo',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
                'usr_deleted_at' => null,
            ],
        ];

        $productIds = [];
        foreach ($products as $p) {
            $productIds[] = DB::table('products')->insertGetId($p);
        }

        $this->command->info("✓ Products created: " . implode(', ', $productIds));

        DB::table('carts')->insert([
            'crs_time' => now(),
            'crs_user_id' => $pembeliId,
            'crs_product_id' => $productIds[0],
            'crs_created_by' => $pembeliId,
            'crs_updated_by' => $pembeliId,
            'crs_sys_note' => 'Demo cart',
            'crs_created_at' => now(),
            'crs_updated_at' => now(),
            'crs_deleted_at' => null,
        ]);

        DB::table('carts')->insert([
            'crs_time' => now(),
            'crs_user_id' => $pembeliId,
            'crs_product_id' => $productIds[1],
            'crs_created_by' => $pembeliId,
            'crs_updated_by' => $pembeliId,
            'crs_sys_note' => 'Demo cart',
            'crs_created_at' => now(),
            'crs_updated_at' => now(),
            'crs_deleted_at' => null,
        ]);

        $this->command->info("✓ Cart items created");

        $subtotal = 50000 + 15000;
        $invoice = 'INV-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        $transId = DB::table('transactions')->insertGetId([
            'tst_invoice' => $invoice,
            'tst_buyer_id' => $pembeliId,
            'tst_seller_id' => $penjualId,
            'tst_total' => $subtotal,
            'tst_subtotal' => $subtotal,
            'tst_discount' => 0,
            'tst_shipping_cost' => 0,
            'tst_payment_method' => 'midtrans_qr',
            'tst_payment_status' => '1',
            'tst_status' => '1',
            'tst_notes' => 'Transaksi demo',
            'tst_shipping_service' => null,
            'tst_shipping_courier' => null,
            'tst_tracking_code' => null,
            'tst_qr_reference' => null,
            'tst_created_by' => $penjualId,
            'tst_updated_by' => $penjualId,
            'tst_deleted_by' => null,
            'tst_sys_note' => 'Seeder transaksi demo',
            'tst_created_at' => now(),
            'tst_updated_at' => now(),
            'tst_deleted_at' => null,
        ]);

        $this->command->info("✓ Transaction created: {$transId} ({$invoice})");

        DB::table('accountings')->insert([
            'act_user_id' => $penjualId,
            'act_exel_url' => '',
            'act_period_from' => now()->startOfMonth()->toDateString(),
            'act_period_to' => now()->endOfMonth()->toDateString(),
            'act_total_sales' => $subtotal,
            'act_total_items_sold' => 2,
            'act_created_by' => $penjualId,
            'act_updated_by' => $penjualId,
            'act_deleted_by' => null,
            'act_sys_note' => 'Seeder laporan demo',
            'act_created_at' => now(),
            'act_updated_at' => now(),
            'act_deleted_at' => null,
        ]);

        $this->command->info("✓ Accounting record created");


        $this->command->info("🎉 InitialDataSeeder completed successfully.");
    }
}
