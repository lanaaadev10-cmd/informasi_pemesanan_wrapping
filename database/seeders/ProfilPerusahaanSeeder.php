<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilPerusahaan;

class ProfilPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPerusahaan::updateOrCreate(
            ['id' => 1],
            [
                'nama_perusahaan' => 'Dantie Wrapping',
                'deskripsi' => 'Transformasikan kendaraan dan bisnis Anda dengan sentuhan profesional dari tim ahli kami.',
                'alamat' => 'Jl. Contoh No. 123, Kota Anda',
                'email' => 'info@dantiewrapping.com',
                'nomor_telepon' => '628123456789',
                'maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.5731164!3d-6.9034443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146050390e48606c!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1672530000000!5m2!1sen!2sid',
                
                // Homepage CMS
                'home_title' => "Make these \n <span class='text-gradient italic'>phenomenal.</span>",
                'home_subtitle' => 'Transformasikan kendaraan dan bisnis Anda dengan sentuhan profesional dari tim ahli kami.',
                'home_prof_title' => "Designed for \n Professionals",
                'home_prof_subtitle' => 'Memberikan solusi wrapping dan stiker yang presisi untuk kebutuhan bisnis skala besar maupun personal.',
                'home_catalog_title' => 'Pilihan Paket <span class="text-gradient">Terbaik.</span>',
                'home_catalog_subtitle' => 'Pilih dari berbagai layanan wrapping dan stiker premium kami yang telah dikurasi untuk hasil maksimal.',
                'home_recent_title' => "Recent \n <span class='text-orange-500 italic'>Bookings.</span>",
                'home_recent_subtitle' => 'Witness the transformation. Real-time updates from our workshop and premium clients.',
                'home_cta_title' => 'Ready to \n Transform?',
                'home_cta_subtitle' => 'Jadikan kendaraan atau bisnis Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim ahli kami.',

                // Profil (About) CMS
                'about_hero_title' => 'Bring everyone together with <br> <span class="text-gradient">solutions that scale.</span>',
                'about_hero_subtitle' => 'Kami hadir dengan dedikasi penuh untuk memberikan hasil terbaik bagi bisnis dan kendaraan Anda melalui inovasi stiker yang tak tertandingi.',
                'stats_experience' => '5+',
                'stats_projects' => '1.2k',
                'stats_satisfaction' => '99%',
                'stats_support' => '24h',
                'about_feature_title' => 'Pengerjaan Cepat & Presisi',
                'about_feature_desc' => 'Setiap pengerjaan kami melewati proses kontrol kualitas yang ketat untuk memastikan hasil akhir yang sempurna bagi Anda.',
                'about_feature_list' => [
                    ['item' => 'Bahan Vinyl Kualitas Dunia'],
                    ['item' => 'Mesin Cetak Berteknologi Tinggi'],
                    ['item' => 'Proses Kontrol Kualitas Ketat'],
                    ['item' => 'Garansi Hasil Pengerjaan'],
                ],

                // Auth Pages
                'login_title' => 'Level Up Your <br> <span class="text-orange-500">Business.</span>',
                'login_subtitle' => 'Masuk ke member area untuk mengelola pesanan dan melihat katalog wrapping terbaik kami.',
                'login_form_title' => 'Welcome Back!',
                'login_form_subtitle' => 'Silakan masuk untuk melanjutkan.',
                'register_title' => 'Create Your <br> <span class="text-orange-500">Journey.</span>',
                'register_subtitle' => 'Bergabunglah dengan ribuan pelanggan puas kami dan dapatkan layanan wrapping terbaik untuk aset berharga Anda.',
                'register_form_title' => 'Get Started!',
                'register_form_subtitle' => '"Mulai pengalaman premium Anda bersama kami."',

                // Dashboard
                'dashboard_title' => 'Welcome back,',
                'dashboard_subtitle' => 'Senang melihat Anda kembali. Apa rencana Anda hari ini?',
            ]
        );
    }
}
