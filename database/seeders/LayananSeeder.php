<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            // ── JASA PASANG SAJA (PELANGGAN BAWA BAHAN SENDIRI) ──
            [
                'nama_layanan'   => 'Jasa Pasang Decal Motor Kecil (Bahan Sendiri)',
                'tipe_paket'     => 'jasa-pasang',
                'deskripsi'      => 'Ongkos jasa pasang decal/stiker untuk motor ukuran kecil. Cocok bagi Anda yang sudah membeli bahan/stiker sendiri di marketplace dan ingin hasil pemasangan rapi, presisi, tanpa gelembung.',
                'fitur'          => [
                    ['nama_fitur' => 'Bawa Stiker Sendiri (Hanya Bayar Jasa)'],
                    ['nama_fitur' => 'Cocok: Beat, Scoopy, Genio, Vario 125/160, Mio, Fazzio'],
                    ['nama_fitur' => 'Pembersihan Bodi & Pemasangan Presisi Tanpa Gelembung'],
                    ['nama_fitur' => 'Finishing Heatgun Anti-Mengelupas'],
                ],
                'harga'          => 100000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '2-3 Jam',
                'jenis_layanan'  => 'jasa_pasang',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/jasa-pasang-decal.jpg',
            ],
            [
                'nama_layanan'   => 'Jasa Pasang Decal Motor Maxi (Bahan Sendiri)',
                'tipe_paket'     => 'jasa-pasang',
                'deskripsi'      => 'Ongkos jasa pasang decal bodi penuh untuk motor matic bongsor/maxi. Dikerjakan oleh teknisi berpengalaman dengan pembongkaran bodi yang rapi dan pengeleman presisi pada lekukan bodi lebar.',
                'fitur'          => [
                    ['nama_fitur' => 'Bawa Stiker Sendiri (Hanya Bayar Jasa)'],
                    ['nama_fitur' => 'Cocok: NMAX, PCX 150/160, Aerox, ADV, Lexi, Vespa Matic'],
                    ['nama_fitur' => 'Bongkar Pasang Bodi Rapi & Presisi'],
                    ['nama_fitur' => 'Garansi Kerapian Lekukan & Sambungan'],
                ],
                'harga'          => 150000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '3-4 Jam',
                'jenis_layanan'  => 'jasa_pasang',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/jasa-pasang-decal.jpg',
            ],
            [
                'nama_layanan'   => 'Jasa Pasang Decal Motor Sport & Fairing (Bahan Sendiri)',
                'tipe_paket'     => 'jasa-pasang',
                'deskripsi'      => 'Ongkos jasa pasang decal khusus motor sport dengan lekukan fairing dan tangki yang kompleks. Hasil simetris, presisi, dan rapi hingga ke sudut-sudut bodi tajam.',
                'fitur'          => [
                    ['nama_fitur' => 'Bawa Stiker Sendiri (Hanya Bayar Jasa)'],
                    ['nama_fitur' => 'Cocok: Ninja 250, CBR 150/250, R15, R25, GSX-R150, MT25'],
                    ['nama_fitur' => 'Pemasangan Khusus Lekukan Tajam & Tangki Bensin'],
                    ['nama_fitur' => 'Finishing Knifeless Tape Rapi Tanpa Goresan'],
                ],
                'harga'          => 250000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '4-6 Jam',
                'jenis_layanan'  => 'jasa_pasang',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/jasa-pasang-decal.jpg',
            ],
            [
                'nama_layanan'   => 'Jasa Pasang Wrapping Panel Mobil (Kap / Atap / Spion)',
                'tipe_paket'     => 'jasa-pasang',
                'deskripsi'      => 'Ongkos jasa pemasangan stiker/scotlite/carbon untuk bagian panel mobil tertentu. Pelanggan membawa stiker sendiri, teknisi kami yang mengeksekusi pemasangan secara profesional.',
                'fitur'          => [
                    ['nama_fitur' => 'Bawa Stiker Sendiri (Hanya Bayar Jasa)'],
                    ['nama_fitur' => 'Cocok: Kap Mesin, Atap, Pintu Bagasi, Spion, Grill'],
                    ['nama_fitur' => 'Teknik Pemasangan Basah/Kering dengan Heatgun Presisi'],
                    ['nama_fitur' => 'Potongan Knifeless Tape Tanpa Risiko Gores Cat Orisinil'],
                ],
                'harga'          => 250000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '3-4 Jam',
                'jenis_layanan'  => 'jasa_pasang',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/jasa-pasang-mobil.jpg',
            ],

            // ── PAKET FULL WRAP (BAHAN TOKO + PASANG BERGARANSI) ──
            [
                'nama_layanan'   => 'Full Wrap Motor Kecil (Bahan Oracal + Garansi)',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Paket lengkap wrapping bodi motor matic/bebek kecil. Menggunakan bahan vinyl premium Oracal 651 Grade-A Jerman yang lentur, warna awet tahan panas matahari, dan aman untuk cat bawaan pabrik.',
                'fitur'          => [
                    ['nama_fitur' => 'Paket Lengkap: Bahan Oracal 651 Toko + Jasa Pasang'],
                    ['nama_fitur' => 'Cocok: Beat, Scoopy, Genio, Vario 125/160, Mio, Fazzio'],
                    ['nama_fitur' => 'Pilihan Finishing Glossy, Matte, Satin & Garansi 1 Tahun'],
                    ['nama_fitur' => 'Aman untuk Cat Asli Motor saat Dilepas Kelak'],
                ],
                'harga'          => 450000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '1 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrapping-motor.jpg',
            ],
            [
                'nama_layanan'   => 'Full Wrap Motor Maxi (Bahan Oracal + Garansi)',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Paket komplit wrapping motor matic besar/maxi scooter. Melindungi seluruh permukaan bodi dari baret harian dan paparan sinar UV dengan pilihan finishing premium bergaransi.',
                'fitur'          => [
                    ['nama_fitur' => 'Paket Lengkap: Bahan Oracal / Maxdecal Toko + Jasa Pasang'],
                    ['nama_fitur' => 'Cocok: NMAX, PCX 150/160, Aerox, ADV, Lexi, Vespa Sprint/Primavera'],
                    ['nama_fitur' => 'Bongkar Bodi Lengkap, Pembungkusan sampai Lekukan Dalam'],
                    ['nama_fitur' => 'Garansi Pemasangan 1 Tahun & Free Maintenance 1x'],
                ],
                'harga'          => 750000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '1-2 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrap-motor-maxi.jpg',
            ],
            [
                'nama_layanan'   => 'Full Wrap Motor Sport (Bahan Oracal + Garansi)',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Paket all-in wrapping motor sport full fairing & naked bike. Hasil akhir mewah melindungi bodi, fairing, serta tangki bensin dari goresan jaket atau kerikil jalanan.',
                'fitur'          => [
                    ['nama_fitur' => 'Paket Lengkap: Bahan Oracal / Maxdecal Toko + Jasa Pasang'],
                    ['nama_fitur' => 'Cocok: Ninja 250, CBR 150/250, R15, R25, GSX, MT-25'],
                    ['nama_fitur' => 'Full Fairing, Tangki, Bodi Belakang & Spakbor Depan'],
                    ['nama_fitur' => 'Garansi Pemasangan 1 Tahun & Sealer Edge Protection'],
                ],
                'harga'          => 1100000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '2 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrapping-motor.jpg',
            ],
            [
                'nama_layanan'   => 'Variasi Mobil', // Menjaga ID 1 tetap sinkron
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Paket all-in wrapping mobil City Car / Hatchback / Sedan Kompak. Menggunakan vinyl premium grade otomotif yang melindungi cat orisinil mobil dari goresan kerikil dan cuaca ekstrem.',
                'fitur'          => [
                    ['nama_fitur' => 'Paket Lengkap: Bahan Oracal/Maxdecal Toko + Jasa Pasang'],
                    ['nama_fitur' => 'Cocok: Brio, Agya, Ayla, Yaris, Jazz, Ignis, Raize, Rocky, City'],
                    ['nama_fitur' => 'Garansi Resmi Pemasangan & Gelembung 3 Tahun'],
                    ['nama_fitur' => 'Pengerjaan Indoor Bebas Debu dengan Detailing Lipatan Pintu'],
                ],
                'harga'          => 3500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '3-4 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrapping-mobil.jpg',
            ],
            [
                'nama_layanan'   => 'Full Wrap Mobil SUV / MPV Besar (Bahan Toko + Garansi)',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Paket lengkap wrapping bodi penuh untuk kendaraan roda empat ukuran menengah hingga besar (MPV/SUV). Transformasi tampilan mobil menjadi lebih elegan dan gagah dengan proteksi maksimal.',
                'fitur'          => [
                    ['nama_fitur' => 'Paket Lengkap: Bahan Premium Toko (Oracal/TeckWrap) + Jasa'],
                    ['nama_fitur' => 'Cocok: Avanza, Xpander, Innova Zenix/Reborn, Fortuner, Pajero, CR-V, HR-V'],
                    ['nama_fitur' => 'Garansi Gelembung & Daya Rekat 3 Tahun'],
                    ['nama_fitur' => 'Free Coating Sealant Khusus Vinyl & Free Kontrol 1x'],
                ],
                'harga'          => 5500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '4-5 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrapping-mobil.jpg',
            ],

            // ── WRAPPING PARSIAL & LAYANAN SPESIALIS ──
            [
                'nama_layanan'   => 'Custom Decal Mobil',
                'tipe_paket'     => 'custom',
                'deskripsi'      => 'Pemasangan decal custom, livery balap, grafis anime, dan striping aksen presisi untuk bodi mobil Anda. Konsep desain, pilihan material, dan estimasi biaya dinegosiasikan langsung via WhatsApp.',
                'fitur'          => [
                    ['nama_fitur' => 'Desain Custom Eksklusif Sesuai Konsep Impian Anda'],
                    ['nama_fitur' => 'Bahan Vinyl Premium / Printable Decal Standar Otomotif'],
                    ['nama_fitur' => 'Lapisan Laminasi Glossy / Doff Tahan Cuaca & Panas'],
                    ['nama_fitur' => 'Konsultasi Desain & Kesepakatan Harga via WhatsApp'],
                    ['nama_fitur' => 'Garansi Kerapian Pemasangan Teknisi Profesional'],
                ],
                'harga'          => 0,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1-3 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'custom',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/wrapping-mobil.jpg',
            ],
            [
                'nama_layanan'   => 'Kaca Film', // Menjaga ID 2 tetap sinkron
                'tipe_paket'     => 'window-film',
                'deskripsi'      => 'Pemasangan kaca film tolak panas premium dengan perlindungan UV 99% dan visibilitas optimal dari dalam ke luar kabin. Mengurangi suhu kabin secara signifikan dan menjaga privasi.',
                'fitur'          => [
                    ['nama_fitur' => 'Anti UV 99% & Tolak Panas IR 80%'],
                    ['nama_fitur' => 'Pilihan Kegelapan Bebas: 40%, 60%, atau 80%'],
                    ['nama_fitur' => 'Garansi Gelembung & Warna Pudar 5 Tahun'],
                    ['nama_fitur' => 'Tersedia Paket Kaca Depan Maupun Full Body Mobil'],
                ],
                'harga'          => 350000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/kaca-film.jpg',
            ],
            [
                'nama_layanan'   => 'Audio Mobil', // Menjaga ID 3 tetap sinkron
                'tipe_paket'     => 'audio',
                'deskripsi'      => 'Instalasi dan penyetelan sistem audio mobil berkualitas audiophile. Mulai dari penggantian speaker split 2-way, penambahan amplifier, subwoofer aktif, hingga instalasi peredam suara kabin.',
                'fitur'          => [
                    ['nama_fitur' => 'Sound Tuning Profesional Sesuai Karakter Musik'],
                    ['nama_fitur' => 'Instalasi Kabel Rapi, Aman, dan Menggunakan Fuse Sekring Khusus'],
                    ['nama_fitur' => 'Peredaman Akustik Pintu Bebas Resonansi'],
                    ['nama_fitur' => 'Garansi Instalasi & Kelistrikan 2 Tahun'],
                ],
                'harga'          => 2000000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '2-4 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/audio-head.jpg',
            ],
            [
                'nama_layanan'   => 'Lampu Biled',
                'tipe_paket'     => 'lighting',
                'deskripsi'      => 'Upgrade sistem pencahayaan proyektor Bi-LED untuk mobil atau motor. Memberikan sorot cahaya terang, fokus, dan cut-off rata yang tidak menyilaukan pengendara lain dari arah berlawanan.',
                'fitur'          => [
                    ['nama_fitur' => 'Proyektor Lensa Bi-LED Terang Maksimal & Fokus'],
                    ['nama_fitur' => 'Kelistrikan Aman Menggunakan Relay Set Tahan Air'],
                    ['nama_fitur' => 'Bisa untuk Headlamp Mobil Maupun Motor Matic/Sport'],
                    ['nama_fitur' => 'Garansi Unit & Instalasi 1 Tahun'],
                ],
                'harga'          => 850000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/lampu-biled.jpg',
            ],
            [
                'nama_layanan'   => 'Striping',
                'tipe_paket'     => 'striping',
                'deskripsi'      => 'Pemasangan striping dan grafis cutting presisi untuk motor maupun mobil. Memberikan aksen sporty minimalis atau racing look sesuai konsep yang Anda inginkan.',
                'fitur'          => [
                    ['nama_fitur' => 'Desain Striping Custom Presisi Cutting Komputer'],
                    ['nama_fitur' => 'Bahan Vinyl Tahan Cuaca Tidak Mudah Pudar'],
                    ['nama_fitur' => 'Cocok untuk Motor Maupun Mobil'],
                    ['nama_fitur' => 'Pemasangan Cepat & Rapi Bergaransi Kerapian'],
                ],
                'harga'          => 200000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '1-2 Jam',
                'jenis_layanan'  => 'paket',
                'tipe_layanan'   => 'fix',
                'is_active'      => true,
                'foto_contoh'    => 'images/layanan/layanan-striping.jpg',
            ],
        ];

        foreach ($layanans as $data) {
            Layanan::updateOrCreate(['nama_layanan' => $data['nama_layanan']], $data);
        }
    }
}
