<?php

namespace App\Helpers;

/**
 * StaticContent — Centralized static data for public-facing pages.
 *
 * All text, labels, stats, and gallery data below are hardcoded
 * and managed ONLY by the developer via code changes.
 * The admin client cannot edit these from the Filament panel.
 *
 * ── How to edit ──
 * 1. Open this file.
 * 2. Find the section you want to change.
 * 3. Edit the value directly.
 * 4. Done — no migrations, no seeder, no cache clear needed.
 *
 * @see \App\Providers\SettingsServiceProvider (still serves internal dashboard settings)
 */
class StaticContent
{
    // ──────────────────────────────────────────────
    //  NAVBAR — Logo, brand, menu labels
    // ──────────────────────────────────────────────
    const APP_NAME      = 'Wapping';
    const NAV_BERANDA   = 'Beranda';
    const NAV_LAYANAN   = 'Layanan';
    const NAV_GALERI    = 'Galeri';
    const NAV_TENTANG   = 'Tentang Kami';
    const NAV_MASUK     = 'Masuk';
    const NAV_DAFTAR    = 'Daftar';
    const NAV_PESANAN   = 'Pemesanan';
    const NAV_DASHBOARD = 'Dashboard';
    const NAV_KELUAR    = 'Keluar';

    // ──────────────────────────────────────────────
    //  PROFIL PERUSAHAAN  (halaman /profil-perusahaan)
    // ──────────────────────────────────────────────
    const COMPANY_NAME        = 'Wapping Premium Wrap';
    const COMPANY_DESCRIPTION = 'Transforming high-performance assets into personalized masterpieces through unparalleled craftsmanship.';
    const COMPANY_ADDRESS     = 'Jl. Wrapping Indah No. 99, Jakarta Selatan, Indonesia 12950';
    const COMPANY_EMAIL       = 'hello@wapping.id';
    const COMPANY_PHONE       = '628123456789';
    const COMPANY_WHATSAPP    = 'https://wa.me/628123456789';
    const COMPANY_MAPS_URL    = 'https://maps.google.com/?q=-6.1234,106.5678';
    const COMPANY_LOGO        = 'images/logo/logo-wapping.png';

    const STATS_PROJECTS      = '98%';       // Client Satisfaction stat
    const VISI                = 'Menjadi studio wrapping terdepan yang dikenal dengan kualitas premium dan inovasi berkelanjutan dalam industri modifikasi estetika kendaraan.';
    const MISI                = 'Memberikan solusi wrapping berkualitas tinggi dengan menggunakan material premium dunia, teknik instalasi presisi, dan komitmen layanan pelanggan yang tiada tanding.';

    // ──────────────────────────────────────────────
    //  HERO SECTION  (halaman beranda)
    // ──────────────────────────────────────────────
    const HERO_BADGE     = 'Professional Car Wrapping Indonesia';
    const HERO_TITLE_1   = 'Elevasi Estetika';
    const HERO_TITLE_2   = 'Aset Mewah Anda.';
    const HERO_SUBTITLE  = 'Layanan premium yang melindungi dan memperindah mobil kesayangan Anda. Hubungi kami untuk penawaran terbaik.';
    const HERO_STAT1_VAL = '500+';
    const HERO_STAT1_LBL = 'Supercars Wrapped';
    const HERO_STAT2_VAL = '5 Tahun';
    const HERO_STAT2_LBL = 'Garansi Material';

    const CTA_PESAN_SEKARANG = 'Pesan Sekarang';
    const CTA_LIHAT_SEMUA    = 'Lihat Portofolio';
    const CTA_SELENGKAPNYA   = 'Selengkapnya';
    const CTA_CEK_SYARAT     = 'Cek Syarat & Ketentuan';
    const CTA_HUBUNGI_WA     = 'Hubungi WhatsApp';
    const CTA_PELAJARI       = 'Pelajari Prosedur';

    // ──────────────────────────────────────────────
    //  KEUNGGULAN SECTION  (halaman beranda)
    // ──────────────────────────────────────────────
    const KEUNGGULAN_BADGE  = 'Keunggulan Layanan';
    const KEUNGGULAN_TITLE  = 'Mengapa Memilih <span class="relative inline-block pb-2">Wapping<span class="absolute bottom-0 left-0 w-full h-[3px] bg-[#f2994a] rounded-full"></span></span>?';

    const K1_TITLE = 'Kualitas Material Grade-A';
    const K1_DESC  = 'Kami hanya menggunakan merk premium dunia seperti <span class="text-white font-semibold">Avery Dennison, 3M, dan Teckwrap</span>. Memberikan hasil akhir yang sangat rapi, warna yang tahan lama, serta perlindungan cat orisinil mobil yang maksimal.';
    const K2_TITLE = 'Teknisi Tersertifikasi';
    const K2_DESC  = 'Dikerjakan oleh tim profesional yang terlatih dan memiliki sertifikasi resmi di bidang car wrapping untuk menjamin ketelitian tinggi.';
    const K3_TITLE = 'Pengerjaan Tepat Waktu';
    const K3_DESC  = 'Kami menghargai waktu berharga Anda. Dengan SOP terstruktur, kami menjamin kendaraan Anda selesai dikerjakan sesuai estimasi waktu.';
    const K4_TITLE = 'Garansi Hingga 5 Tahun';
    const K4_DESC  = 'Kami sangat yakin atas kualitas pengerjaan dan ketahanan bahan yang kami berikan. Nikmati perlindungan garansi penuh hingga 5 tahun untuk kepuasan total Anda.';

    // ──────────────────────────────────────────────
    //  PORTOFOLIO SECTION  (halaman beranda)
    // ──────────────────────────────────────────────
    const PORTOFOLIO_BADGE = 'Showcase Portofolio';
    const PORTOFOLIO_TITLE = 'Mahakarya Kami';
    const PORTOFOLIO_DESC  = 'Berikut adalah hasil pengerjaan car wrapping premium dari tim ahli profesional kami.';

    // ──────────────────────────────────────────────
    //  CTA + LANGKAH SECTION  (halaman beranda)
    // ──────────────────────────────────────────────
    const CTA_TITLE    = 'Siap Mengubah Tampilan Kendaraan?';
    const CTA_SUBTITLE = 'Hubungi konsultan desain gratis sekarang. Tim ahli kami akan membantu Anda memilih material dan warna terbaik yang sesuai dengan kepribadian Anda.';

    const STEP1_TITLE = 'Konsultasi & Estimasi';
    const STEP1_DESC  = 'Hubungi tim admin kami untuk berkonsultasi mengenai desain & biaya.';
    const STEP2_TITLE = 'Pilihan Wrapping';
    const STEP2_DESC  = 'Tentukan pilihan material, merk premium, dan warna sesuai keinginan Anda.';
    const STEP3_TITLE = 'Pengerjaan Rapi';
    const STEP3_DESC  = 'Bawa mobil Anda ke workshop kami dan serahkan pengerjaan pada ahlinya.';

    const LANGKAH_BADGE   = 'Langkah Mudah';
    const LANGKAH_TAGLINE = 'Fast Process';

    // ──────────────────────────────────────────────
    //  FOOTER
    // ──────────────────────────────────────────────
    const FOOTER_TENTANG        = 'Tentang Kami';
    const FOOTER_LAYANAN        = 'Layanan';
    const FOOTER_PRIVASI        = 'Kebijakan Privasi';
    const FOOTER_HUBUNGI        = 'Hubungi Kami';
    const FOOTER_COPYRIGHT      = '&copy; 2026 Wapping Premium Wrapping. Hak Cipta Dilindungi.';
    const FOOTER_INSTAGRAM      = 'Instagram';
    const FOOTER_FACEBOOK       = 'Facebook';
    const FOOTER_NAVIGASI       = 'Navigasi';
    const FOOTER_LOKASI         = 'Lokasi Kami';

    const INSTAGRAM_URL = 'https://instagram.com/wapping.id';
    const FACEBOOK_URL  = 'https://facebook.com/wapping.id';
    const TIKTOK_URL    = 'https://tiktok.com/@wapping.id';

    // ──────────────────────────────────────────────
    //  GALERI  (halaman /galeri-karya)
    // ──────────────────────────────────────────────
    const GALERI_TITLE       = 'Precision Mastery Gallery';
    const GALERI_DESC        = 'Explore our curated selection of high-end automotive transformations. From matte finishes to protective layers, witness the art of precision in every detail.';
    const GALERI_FILTER_ALL  = 'All Works';
    const GALERI_EMPTY_STATE = 'Belum ada galeri untuk ditampilkan.';

    /**
     * Static gallery items.
     * Each item: ['judul', 'foto', 'deskripsi', 'kategori', 'badge_text']
     */
    public static function galeriItems(): array
    {
        return [
            [
                'judul'     => 'Tesla Model S',
                'foto'      => 'images/galeri/tesla-model-s.jpg',
                'deskripsi' => 'Luxury Matte Grey / Blue — full body satin wrap with gloss black accents.',
                'kategori'  => 'matte',
                'badge_text' => 'Varian Favorit',
            ],
            [
                'judul'     => 'Range Rover Sport',
                'foto'      => 'images/galeri/range-rover-sport.jpg',
                'deskripsi' => 'Satin Liquid Silver Wrap — premium finish with ceramic coating protection.',
                'kategori'  => 'satin',
                'badge_text' => 'Sangat Direkomendasikan',
            ],
            [
                'judul'     => 'Ferrari F8 Tributo',
                'foto'      => 'images/galeri/ferrari-f8.jpg',
                'deskripsi' => 'Satin Metallic Gold Yellow — a head-turning transformation for this Italian masterpiece.',
                'kategori'  => 'satin',
                'badge_text' => '',
            ],
            [
                'judul'     => 'Porsche 911 GT3',
                'foto'      => 'images/galeri/porsche-911.jpg',
                'deskripsi' => 'Matte Racing Green — aggressive yet elegant, track-ready aesthetic.',
                'kategori'  => 'matte',
                'badge_text' => 'Unggulan',
            ],
            [
                'judul'     => 'Mercedes-Benz S-Class',
                'foto'      => 'images/galeri/mercedes-s-class.jpg',
                'deskripsi' => 'Gloss Diamond White — mirror finish that exudes pure luxury.',
                'kategori'  => 'glossy',
                'badge_text' => '',
            ],
            [
                'judul'     => 'Lamborghini Urus',
                'foto'      => 'images/galeri/lamborghini-urus.jpg',
                'deskripsi' => 'Satin Armour Grey — stealthy SUV wrap with custom carbon accents.',
                'kategori'  => 'satin',
                'badge_text' => 'Best Seller',
            ],
        ];
    }

    /**
     * Gallery filter categories.
     */
    public static function galeriCategories(): array
    {
        return [
            ['slug' => 'matte',  'label' => 'Matte Series'],
            ['slug' => 'satin',  'label' => 'Satin Series'],
            ['slug' => 'glossy', 'label' => 'Glossy Series'],
        ];
    }

    // ──────────────────────────────────────────────
    //  LAYANAN  (halaman /layanan)
    // ──────────────────────────────────────────────
    const LAYANAN_HERO_TITLE = 'Precision in Every Layer.';
    const LAYANAN_HERO_DESC  = 'Pilih paket perlindungan dan estetika terbaik untuk kendaraan Anda. Menggunakan material grade premium dengan pemasangan yang sangat mendetail.';
    const LAYANAN_BADGE      = 'Layanan & Paket';
    const LAYANAN_CARD_BTN   = 'Pesan Sekarang';
    const LAYANAN_EMPTY      = 'admin belum menambahkan paket layanan saat ini.';

    const LAYANAN_MENGAPA_TITLE = 'Mengapa Memilih Kami?';
    const LAYANAN_MENGAPA_DESC  = 'Kami menggunakan keahlian teknis dengan material terbaik dunia untuk memastikan aset Anda terlindung sempurna. Setiap pengerjaan dilakukan di ruangan steril dengan kontrol suhu untuk hasil yang maksimal tanpa gelembung udara.';
    const LAYANAN_GARANSI_TITLE = 'Garansi Resmi';
    const LAYANAN_GARANSI_DESC  = 'Hingga 5 tahun perlindungan terhadap gelembung, pengelupasan, dan kerusakan perekatan.';

    const LAYANAN_BENEFIT_1 = 'Instalatur Bersertifikat';
    const LAYANAN_BENEFIT_2 = 'Ruangan Steril';
    const LAYANAN_BENEFIT_3 = 'Quality Control 3 Lapis';

    // ──────────────────────────────────────────────
    //  TENTANG KAMI  (halaman /tentang-kami)
    // ──────────────────────────────────────────────
    const TENTANG_HERO_BADGE    = 'Tentang Kami';
    const TENTANG_HERO_TITLE    = 'Precision in Every Layer';
    const TENTANG_HERO_DESC     = 'Satu pilihan terbaik untuk menjaga kendaraan Anda tetap berkilau dan melindunginya dari goresan, jamur, serta kotoran jalanan demi performa yang selalu cemerlang.';

    const SEJARAH_BADGE    = 'Sejarah Kami';
    const SEJARAH_TITLE    = 'Satu Dekade Dedikasi pada Perfeksi.';
    const VISI_TITLE       = 'Visi Kami';
    const MISI_TITLE       = 'Misi Kami';
    const VALUES_TITLE     = 'Nilai yang Kami Junjung';

    const TIM_BADGE  = 'Tim Kami';
    const TIM_TITLE  = 'Dibalik Setiap Detail Sempurna.';
    const TIM_DESC   = 'Didukung oleh mekanik bersertifikat dan berdedikasi tinggi yang memastikan setiap pemasangan stiker berjalan dengan sempurna dan presisi.';

    const CTA_TENTANG_TITLE  = 'Siap Mengubah Tampilan Kendaraan Anda?';
    const CTA_TENTANG_DESC   = 'Jadikan kendaraan Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim kami yang berpengalaman.';
    const CTA_TENTANG_BTN    = 'Hubungi Kami Sekarang';
    const CTA_TENTANG_SEC    = 'Lihat Portofolio';

    /**
     * Team members.
     */
    public static function teamMembers(): array
    {
        return [
            [
                'nama'   => 'Adrian Wijaya',
                'posisi' => 'Master Wrapper',
                'foto'   => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'nama'   => 'Siska Pratama',
                'posisi' => 'Lead Designer',
                'foto'   => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'nama'   => 'Budi Santoso',
                'posisi' => 'Detailing Specialist',
                'foto'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'nama'   => 'Kevin Rahardja',
                'posisi' => 'Operation Manager',
                'foto'   => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=600&auto=format&fit=crop',
            ],
        ];
    }
}
