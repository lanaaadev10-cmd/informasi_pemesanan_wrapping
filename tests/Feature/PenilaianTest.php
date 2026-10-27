<?php

use App\Models\DetailPesanan;
use App\Models\Layanan;
use App\Models\Penilaian;
use App\Models\Pesanan;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Pastikan peran pengguna dan admin tersedia
    Artisan::call('db:seed', [
        '--class' => RolesTableSeeder::class,
    ]);

    $this->actor = User::factory()->create();
    $this->actAs = fn () => $this->actingAs($this->actor);

    $this->layanan = Layanan::create([
        'nama_layanan' => 'Variasi Mobil',
        'harga' => 500000,
        'tipe_layanan' => 'fix',
        'kategori' => 'mobil',
    ]);
});

function createSelesaiPesanan(User $user, Layanan $layanan): Pesanan
{
    $pesanan = Pesanan::create([
        'id_user' => $user->id,
        'kode_pesanan' => 'PSN-TEST-'.strtoupper(uniqid()),
        'tanggal_pesan' => now(),
        'status' => Pesanan::STATUS_SELESAI,
        'total_harga' => 500000,
    ]);

    DetailPesanan::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_paket' => $layanan->id_layanan,
        'jumlah' => 1,
        'harga_satuan' => 500000,
        'subtotal' => 500000,
    ]);

    return $pesanan;
}

describe('Alur 1 - Penilaian via Pesanan Produk/Paket', function () {
    test('tamu tanpa autentikasi tidak dapat membuka form penilaian', function () {
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->get(route('pesanan.rating.form', $pesanan->id_pesanan))
            ->assertRedirect(route('login'));

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [])
            ->assertRedirect(route('login'));
    });

    test('pesanan yang belum selesai ditolak untuk diberi ulasan', function () {
        ($this->actAs)();

        $pesanan = Pesanan::create([
            'id_user' => $this->actor->id,
            'kode_pesanan' => 'PSN-BELUM-'.strtoupper(uniqid()),
            'tanggal_pesan' => now(),
            'status' => Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
            'total_harga' => 500000,
        ]);

        $this->get(route('pesanan.rating.form', $pesanan->id_pesanan))
            ->assertForbidden();

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ])->assertForbidden();

        $this->assertDatabaseMissing('ratings', ['id_pesanan' => $pesanan->id_pesanan]);
    });

    test('pesanan milik pelanggan lain ditolak aksesnya', function () {
        $other = User::factory()->create();
        $pesanan = createSelesaiPesanan($other, $this->layanan);

        ($this->actAs)();

        $this->get(route('pesanan.rating.form', $pesanan->id_pesanan))
            ->assertNotFound();

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ])->assertNotFound();
    });

    test('simpan penilaian berhasil untuk pesanan selesai', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $response = $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Hasil wrapping sangat memuaskan!',
        ]);

        $response->assertSessionHas('toast_success');
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('ratings', [
            'id_user' => $this->actor->id,
            'id_pesanan' => $pesanan->id_pesanan,
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Hasil wrapping sangat memuaskan!',
        ]);
    });

    test('hanya satu ulasan per layanan per pesanan (update, bukan duplikat)', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 4,
            'ulasan' => 'Ulasan pertama',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Ulasan setelah update',
        ])->assertRedirect(route('dashboard'));

        $this->assertSame(1, Penilaian::where('id_pesanan', $pesanan->id_pesanan)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());

        $this->assertDatabaseHas('ratings', [
            'id_pesanan' => $pesanan->id_pesanan,
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Ulasan setelah update',
        ]);
    });

    test('validasi: nilai bintang wajib 1-5 dan layanan harus ada pada pesanan', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        // Nilai rating di luar batas 1-5
        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 6,
        ])->assertSessionHasErrors('rating');

        // Layanan tidak terdaftar pada pesanan
        $otherLayanan = Layanan::create([
            'nama_layanan' => 'Audio Mobil',
            'harga' => 800000,
            'tipe_layanan' => 'fix',
            'kategori' => 'mobil',
        ]);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $otherLayanan->id_layanan,
            'rating' => 5,
        ])->assertSessionHas('toast_error');

        $this->assertDatabaseMissing('ratings', ['id_pesanan' => $pesanan->id_pesanan]);
    });

    test('pelanggan yang sama boleh memberikan ulasan pada layanan yang sama di pesanan berbeda', function () {
        ($this->actAs)();

        $pesananA = createSelesaiPesanan($this->actor, $this->layanan);
        $pesananB = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesananA->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ])->assertRedirect(route('dashboard'));

        $this->post(route('pesanan.rating.store', $pesananB->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 4,
        ])->assertRedirect(route('dashboard'));

        $this->assertSame(2, Penilaian::where('id_user', $this->actor->id)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());
    });
});

describe('Testimoni Publik', function () {
    test('halaman testimoni hanya menampilkan ulasan dengan is_tampil true', function () {
        ($this->actAs)();

        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Ulasan tampil',
        ]);

        Penilaian::create([
            'id_user' => $this->actor->id,
            'id_layanan' => $this->layanan->id_layanan,
            'order_ref' => 0,
            'rating' => 1,
            'ulasan' => 'Ulasan tersembunyi',
            'is_tampil' => false,
        ]);

        $response = $this->get(route('testimoni.index'));

        $response->assertOk();
        $response->assertSee('Ulasan tampil', false);
        $response->assertDontSee('Ulasan tersembunyi', false);
    });

    test('ringkasan rata-rata dihitung dari semua ulasan yang tampil', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ]);

        Penilaian::create([
            'id_user' => $this->actor->id,
            'id_layanan' => $this->layanan->id_layanan,
            'order_ref' => 0,
            'rating' => 4,
        ]);

        $this->get(route('testimoni.index'))
            ->assertOk()
            ->assertSee('4,5', false);
    });
});
