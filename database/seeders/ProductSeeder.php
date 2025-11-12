<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'prd_name' => 'Sambal keren',
                'prd_description' => 'Top up cepat & aman untuk Mobile Legends. Nikmati promo eksklusif minggu ini!',
                'prd_status' => 'tersedia',
                'prd_price' => 15000,
                'prd_created_by' => 1,
                'usr_sys_note' => 'Produk populer game Mobile Legends',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Raja sambal',
                'prd_description' => 'Isi ulang Unknown Cash (UC) PUBG Mobile resmi, harga murah & proses instan.',
                'prd_status' => 'tersedia',
                'prd_price' => 30000,
                'prd_created_by' => 1,
                'usr_sys_note' => 'Voucher PUBG UC',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Mie sambal',
                'prd_description' => 'Dapatkan VP untuk Valorant sekarang! Cepat, aman, dan terpercaya.',
                'prd_status' => 'tidak tersedia',
                'prd_price' => 50000,
                'prd_created_by' => 1,
                'usr_sys_note' => 'Sambal judas',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Steam Wallet Code 50K',
                'prd_description' => 'Voucher Steam Wallet untuk semua game favorit kamu di Steam.',
                'prd_status' => 'tersedia',
                'prd_price' => 52000,
                'prd_created_by' => 1,
                'usr_sys_note' => 'Sambal kece',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Sambal walik',
                'prd_description' => 'Gunakan Garena Shells untuk game Free Fire, PB, dan lainnya.',
                'prd_status' => 'tidak tersedia',
                'prd_price' => 100000,
                'prd_created_by' => 1,
                'usr_sys_note' => 'Produk sementara habis stok',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
        ]);
    }
}
