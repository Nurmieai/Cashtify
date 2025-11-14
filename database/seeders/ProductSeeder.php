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
                'prd_name' => 'Sambal Korek Super Pedas',
                'prd_description' => 'Sambal korek khas rumahan dengan cita rasa pedas menggigit. Cocok untuk lauk gorengan, ayam, dan nasi hangat.',
                'prd_status' => 'tersedia',
                'prd_price' => 15000,
                'prd_card_url' => 'assets/images/products/sambal1.jpg',
                'prd_created_by' => 1,
                'usr_sys_note' => 'Best seller — pedasnya nagih!',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Sambal Bawang Original',
                'prd_description' => 'Rasa gurih dan pedas dari bawang segar, dibuat tanpa bahan pengawet. Favorit pelanggan setia!',
                'prd_status' => 'tersedia',
                'prd_price' => 18000,
                'prd_card_url' => 'assets/images/products/sambal2.jpeg',
                'prd_created_by' => 1,
                'usr_sys_note' => 'Cocok untuk pecinta bawang dan rasa tradisional.',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Sambal Ijo Padang',
                'prd_description' => 'Sambal ijo khas Padang dengan cabai hijau segar. Tidak terlalu pedas, tapi aromanya wangi dan gurih.',
                'prd_status' => 'tersedia',
                'prd_price' => 20000,
                'prd_card_url' => 'assets/images/products/sambal3.jpg',
                'prd_created_by' => 1,
                'usr_sys_note' => 'Laris di warung nasi Padang!',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Sambal Cumi Hitam',
                'prd_description' => 'Perpaduan cumi segar dan cabai merah pilihan. Rasanya gurih pedas, cocok buat lauk nasi panas.',
                'prd_status' => 'tidak tersedia',
                'prd_price' => 25000,
                'prd_card_url' => 'assets/images/products/sambal4.jpg',
                'prd_created_by' => 1,
                'usr_sys_note' => 'Sementara stok kosong, produksi ulang minggu depan.',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
            [
                'prd_name' => 'Sambal Matah Bali',
                'prd_description' => 'Sambal khas Bali dengan irisan bawang, serai, dan cabai rawit segar. Aroma khasnya bikin nagih.',
                'prd_status' => 'tersedia',
                'prd_price' => 22000,
                'prd_card_url' => 'assets/images/products/sambal5.jpg',
                'prd_created_by' => 1,
                'usr_sys_note' => 'Rekomendasi chef — favorit untuk seafood!',
                'usr_created_at' => now(),
                'usr_updated_at' => now(),
            ],
        ]);
    }
}
