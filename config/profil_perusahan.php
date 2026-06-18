// Konfigurasi statis untuk CMS content yang tidak perlu berubah dinamis di admin.
<?php

/**
 * File: config/profil_perusahan.php
 * 
 * Konfigurasi statis untuk CMS content yang tidak perlu berubah dinamis di admin.
 * Semua nilai di sini adalah DEFAULT/FALLBACK jika data di database tidak ada.
 * 
 * PENTING: Jika Anda perlu mengubah nilai, ubah di file ini, bukan di database.
 * Untuk deployment: commit file ini ke repo, jangan hapus.
 */

return [
    /**
     * ============================================================================
     * BERANDA (Homepage)
     * ============================================================================
     * Field yang STATIS: judul hero, deskripsi fitur, label langkah
     */
    'home' => [
        'title' => 'Transformasi Kendaraan Anda',
        'subtitle' => 'Wrapping berkualitas tinggi dengan material terbaik',
        'hero_image' => 'images/hero-home.jpg',
        
        'feature_title' => 'Mengapa Pilih Kami?',
        'feature_subtitle' => 'Kami menyediakan solusi wrapping terpercaya dengan hasil profesional',
        
        'stat1_value' => '500+',
        'stat1_label' => 'Mobil Telah Dibalut',
        'stat2_value' => '5 Tahun',
        'stat2_label' => 'Garansi Material',
        
        'keunggulan_cards' => [
            [
                'title' => 'Material Premium',
                'desc' => 'Menggunakan material 3M terbaik di kelasnya',
            ],
            [
                'title' => 'Teknisi Berpengalaman',
                'desc' => 'Tim profesional dengan sertifikasi internasional',
            ],
            [
                'title' => 'Garansi Lengkap',
                'desc' => 'Jaminan 5 tahun untuk semua produk',
            ],
            [
                'title' => 'Proses Cepat',
                'desc' => 'Pengerjaan efisien tanpa mengorbankan kualitas',
            ],
        ],
        
        'cta_title' => 'Siap Mengubah Penampilan Kendaraan Anda?',
        'cta_subtitle' => 'Hubungi kami sekarang untuk konsultasi gratis',
        
        'steps' => [
            [
                'number' => '1',
                'title' => 'Pilih Desain',
                'desc' => 'Pilih dari katalog kami atau request custom design',
            ],
            [
                'number' => '2',
                'title' => 'Booking Jadwal',
                'desc' => 'Tentukan jadwal pengerjaan yang sesuai',
            ],
            [
                'number' => '3',
                'title' => 'Proses Wrapping',
                'desc' => 'Pengerjaan profesional di workshop kami',
            ],
        ],
        
        'prof_title' => 'Tentang Kami',
        'catalog_title' => 'Katalog Produk',
        'recent_title' => 'Galeri Terbaru',
        'badge_text' => 'Terpercaya Sejak 2018',
    ],

    /**
     * ============================================================================
     * TENTANG KAMI (About Page)
     * ============================================================================
     */
    'about' => [
        'hero_title' => 'Tentang Perusahaan Kami',
        'hero_subtitle' => 'Komitmen kami adalah memberikan layanan wrapping terbaik di Indonesia',
        
        'pillars' => [
            [
                'title' => 'Kualitas',
                'desc' => 'Kami tidak pernah kompromi dengan kualitas material dan hasil kerja',
            ],
            [
                'title' => 'Inovasi',
                'desc' => 'Terus berkembang dengan teknologi dan desain terkini',
            ],
            [
                'title' => 'Kepercayaan',
                'desc' => 'Ribuan pelanggan telah mempercayai kami',
            ],
        ],
        
        'stats' => [
            'experience' => '5+ Tahun',
            'projects' => '500+',
            'satisfaction' => '98%',
            'support' => '24/7',
        ],
        
        'feature_title' => 'Mengapa Memilih Kami',
        'feature_desc' => 'Berikut adalah alasan mengapa ribuan pelanggan memilih layanan kami',
        'feature_list' => [
            'Material berkualitas tinggi dari brand ternama',
            'Teknisi bersertifikat dan berpengalaman',
            'Proses profesional dengan peralatan modern',
            'Garansi komprehensif hingga 5 tahun',
            'Konsultasi gratis dan support after-sales',
        ],
    ],

    /**
     * ============================================================================
     * LAYANAN (Services)
     * ============================================================================
     * DINAMIS: nama, harga, deskripsi layanan tetap di DB (tabel `layanans`)
     * STATIS: hero section, CTA text, deskripsi garansi
     */
    'services' => [
        'hero_title' => 'Layanan Kami',
        'hero_desc' => 'Pilih paket layanan wrapping terbaik untuk kendaraan Anda',
        'hero_image' => 'images/hero-services.jpg',
        
        'garansi_title' => 'Garansi 5 Tahun',
        'garansi_desc' => 'Semua produk wrapping kami dilindungi garansi material hingga 5 tahun',
        
        'cta_title' => 'Tertarik dengan Layanan Kami?',
        'cta_desc' => 'Hubungi tim kami untuk konsultasi dan pemesanan',
        
        'grid_columns' => 4,
        'card_style' => 'standard',
        'show_benefits' => true,
        'show_warranty' => true,
    ],

    /**
     * ============================================================================
     * KATALOG (Catalog Page)
     * ============================================================================
     */
    'catalog' => [
        'title' => 'Katalog Layanan',
        'subtitle' => 'Lihat semua paket layanan wrapping kami',
        'hero_title' => 'Jelajahi Katalog Lengkap Kami',
        'hero_desc' => 'Temukan inspirasi dari berbagai pilihan desain dan paket layanan',
        'intro_text' => 'Setiap desain dirancang dengan teliti untuk memastikan kepuasan maksimal.',
        
        'empty_state_title' => 'Katalog Kosong',
        'empty_state_desc' => 'Belum ada layanan yang ditambahkan. Silakan hubungi admin.',
        
        'features' => [
            [
                'title' => 'Pilihan Lengkap',
                'desc' => 'Berbagai pilihan desain dan material tersedia',
            ],
            [
                'title' => 'Harga Kompetitif',
                'desc' => 'Harga terjangkau dengan kualitas terjamin',
            ],
            [
                'title' => 'Konsultasi Gratis',
                'desc' => 'Tim expert kami siap membantu pemilihan terbaik',
            ],
            [
                'title' => 'Pengerjaan Cepat',
                'desc' => 'Pengerjaan efisien sesuai jadwal Anda',
            ],
        ],
    ],

    /**
     * ============================================================================
     * GALERI
     * ============================================================================
     */
    'gallery' => [
        'title' => 'Galeri Karya Kami',
        'subtitle' => 'Lihat hasil karya terbaik dari berbagai project',
        'hero_title' => 'Galeri Project Terbaru',
        'hero_desc' => 'Inspirasi dari karya-karya profesional kami',
        'hero_image' => 'images/hero-gallery.jpg',
        
        'intro_text' => 'Setiap project adalah bukti komitmen kami terhadap kualitas.',
        'empty_state_title' => 'Galeri Kosong',
        'empty_state_desc' => 'Belum ada galeri yang ditambahkan.',
    ],

    /**
     * ============================================================================
     * PROFIL PERUSAHAAN
     * ============================================================================
     * STATIS: Teks UI dan copy design untuk halaman profil perusahaan.
     */
    'profile' => [
        'page_title' => 'Profil Perusahaan',

        'header' => [
            'badge' => 'TENTANG KAMI',
            'title' => 'Profil Perusahaan',
            'status' => 'Studio Wrapping Premium',
        ],

        'hero' => [
            'heading' => 'Seni dan Teknik dalam Setiap Detil',
            'description' => 'Kami menggabungkan seni dan teknik untuk menghadirkan wrapping kendaraan yang presisi, tahan lama, dan memukau.',
        ],

        'stats' => [
            'label' => 'Kepuasan Pelanggan',
            'copy' => 'Lebih dari 5.000 kendaraan premium telah kami transformasi dengan perhatian detail yang obsesif.',
        ],

        'master_craft_label' => 'Kerajinan Utama',

        'narrative' => [
            'badge' => 'NARASI KAMI',
            'heading' => 'Warisan Keunggulan',
            'copy' => [
                '1' => 'Lahir dari passion terhadap estetika otomotif dan kesempurnaan teknis, studio wrapping premium kami dimulai sebagai workshop boutique yang berdedikasi untuk marque paling eksklusif dunia.',
                '2' => 'Visi kami adalah mendefinisikan ulang batas antara utilitas dan seni. Kami tidak hanya menerapkan material; kami menciptakan pengalaman yang menghormati rekayasa setiap aset sambil mencerminkan jiwa pemiliknya.',
            ],
            'read_history_button' => 'Baca Selengkapnya',
        ],

        'pillars' => [
            [
                'title' => 'Presisi Teknik',
                'desc' => 'Setiap tepi rapi, setiap sudut mulus dengan template desain CAD dan teknik aplikasi bedah untuk hasil setara pabrik.',
            ],
            [
                'title' => 'Material Pilihan',
                'desc' => 'Kami hanya menggunakan polimer dan adhesive grade tertinggi dari supplier terkemuka dunia untuk ketahanan maksimal.',
            ],
            [
                'title' => 'Layanan White-Glove',
                'desc' => 'Dari konsultasi awal hingga inspeksi final, proses kami dirancang untuk transparansi dan ketenangan pikiran.',
            ],
        ],

        'network' => [
            'section_title' => 'JARINGAN STUDIO GLOBAL',
            'subtitle' => 'Kualitas andalan kami tersedia di berbagai hub utama dunia.',
            'locations' => [
                [
                    'name' => 'Studio Los Angeles',
                    'address' => '7821 Sunset Blvd, CA',
                ],
                [
                    'name' => 'Mashill Team',
                    'address' => '2042 Meydan Rd, Meydan City, Dubai',
                ],
            ],
        ],

        'history_modal' => [
            'badge' => 'NARASI KAMI',
            'title' => 'Sejarah Perusahaan & Visi',
        ],

        'vision' => [
            'label' => 'Visi Kami',
            'default' => 'Menjadi penyedia layanan wrapping dan stiker terpercaya dengan inovasi, kualitas, dan kepuasan pelanggan sebagai prioritas utama.',
        ],

        'mission' => [
            'label' => 'Misi Kami',
            'default' => 'Memberikan solusi wrapping dan stiker berkualitas tinggi dengan harga kompetitif, layanan excellence, dan dukungan purna jual terbaik.',
        ],

        'history' => [
            'section_title' => 'Perjalanan Kami',
            'paragraphs' => [
                '1' => 'Didirikan dengan komitmen teguh terhadap kualitas estetika dan perlindungan kendaraan, kami tumbuh menjadi pilihan utama bagi pemilik kendaraan premium. Dari bengkel kecil dengan impian besar, kini kami mengoperasikan studio modern dengan standar clean-room kelas internasional.',
                '2' => 'Setiap pengerjaan diawasi dengan ketat oleh teknisi bersertifikat internasional. Kami bermitra dengan merek premium terkemuka global untuk menjamin ketahanan, kejernihan, dan keindahan proteksi bodi kendaraan Anda tanpa kompromi sedikit pun.',
            ],
        ],
    ],

    /**
     * ============================================================================
     * HALAMAN PESANAN
     * ============================================================================
     */
    'orders' => [
        'page_title' => 'Pesanan Saya',
        'page_desc' => 'Lihat detail dan status semua pesanan Anda',
        
        'filter_labels' => [
            'semua' => 'Semua Pesanan',
            'berjalan' => 'Sedang Dikerjakan',
            'selesai' => 'Selesai',
        ],
        
        'empty_state_title' => 'Tidak Ada Pesanan',
        'empty_state_desc' => 'Anda belum memiliki pesanan. Mulai pesanan baru sekarang.',
        'new_order_button' => 'Mulai Pesanan Baru',
    ],

    /**
     * ============================================================================
     * HALAMAN KERANJANG
     * ============================================================================
     */
    'cart' => [
        'hero_text' => 'Tinjau pesanan Anda sebelum melanjutkan ke checkout',
        'title' => 'Keranjang Belanja',
        'subtitle' => 'Tinjau item Anda dan pastikan semuanya benar sebelum pembayaran',
        
        'empty_title' => 'Keranjang Kosong',
        'empty_desc' => 'Tidak ada item di keranjang Anda. Mulai belanja sekarang.',
        
        'warranty_title' => 'Garansi Produk',
        'warranty_desc' => 'Semua paket dilindungi garansi hingga 5 tahun',
        
        'service_charge_label' => 'Biaya Layanan',
        'service_charge_amount' => '10%',
        'checkout_button' => 'Lanjut ke Checkout',
    ],

    /**
     * ============================================================================
     * HALAMAN CHECKOUT
     * ============================================================================
     */
    'checkout' => [
        'steps' => [
            '1' => 'Konfirmasi Item',
            '2' => 'Data Pengiriman',
            '3' => 'Pembayaran',
            '4' => 'Selesai',
        ],
        
        'step2_title' => 'Informasi Pengiriman',
        'step2_subtitle' => 'Masukkan detail lokasi dan jadwal pengerjaan',
        
        'step3_title' => 'Tinjau Pesanan',
        'step3_subtitle' => 'Pastikan semua data sudah benar sebelum konfirmasi',
        
        'warranty_badges' => [
            'badge_1' => 'Garansi Material',
            'badge_2' => '5 Tahun',
        ],
        
        'confirm_button' => 'Konfirmasi Pesanan',
    ],

    /**
     * ============================================================================
     * DASHBOARD PELANGGAN
     * ============================================================================
     * DINAMIS: progress member, manfaat member
     * STATIS: label dan judul section
     */
    'dashboard' => [
        'title' => 'Dashboard Anda',
        'subtitle' => 'Kelola pesanan dan preferensi Anda',
        
        'member_title' => 'Status Member Premium',
        'member_desc' => 'Tingkatkan level membership Anda untuk benefit lebih',
        
        'quick_services_title' => 'Layanan Cepat',
        'activity_title' => 'Aktivitas Terbaru',
        
        'empty_title' => 'Tidak Ada Pengerjaan Aktif',
        'empty_desc' => 'Anda tidak memiliki pesanan yang sedang dikerjakan.',
        
        'services' => [
            [
                'title' => 'Perlindungan Cat Film',
                'desc' => 'Lindungi cat kendaraan Anda dengan teknologi terkini',
                'icon' => 'ph-shield',
            ],
            [
                'title' => 'Ubah Warna',
                'desc' => 'Ubah warna kendaraan dengan wrap profesional',
                'icon' => 'ph-palette',
            ],
            [
                'title' => 'Styling Interior',
                'desc' => 'Styling interior dengan sentuhan modern',
                'icon' => 'ph-armchair',
            ],
            [
                'title' => 'Detailing',
                'desc' => 'Perawatan detail untuk hasil sempurna',
                'icon' => 'ph-sparkles',
            ],
        ],
    ],

    /**
     * ============================================================================
     * KONFIGURASI LAYOUT & TEMA
     * ============================================================================
     * STATIS: Pengaturan layout yang jarang berubah
     */
    'layout' => [
        'tentang_kami' => [
            'values_columns' => 3,
            'show_values' => true,
            'show_history' => true,
            'show_team' => true,
        ],
        
        'accent_color' => '#f2994a',
        'primary_layout' => 'full',
        'dark_mode' => true,
    ],

    /**
     * ============================================================================
     * FOOTER & AUTHENTIKASI
     * ============================================================================
     */
    'footer' => [
        'copyright' => '© 2024 Informasi Pemesanan Wrapping. Semua hak dilindungi.',
        'auth_badge' => 'Terpercaya & Bersertifikat',
    ],
];
