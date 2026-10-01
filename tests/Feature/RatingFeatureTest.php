<?php

use App\Models\Booking;
use App\Models\DetailPesanan;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Pastikan role admin/user tersedia untuk listener notifikasi admin.
    // (Seeder global di tests/Pest.php tidak berfungsi di lingkungan DB ini.)
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

function createCompletedBooking(User $user, Layanan $layanan): Booking
{
    return Booking::create([
        'booking_code' => 'BKG-TEST-' . strtoupper(uniqid()),
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'layanan_id' => $layanan->id_layanan,
        'booking_date' => now()->toDateString(),
        'booking_time' => '10:00',
        'payment_type' => 'lunas',
        'vehicle_name' => 'Toyota Avanza',
        'status' => 'completed',
    ]);
}

describe('Alur 1 - rating via pesanan', function () {
    test('guest tidak bisa membuka form rating', function () {
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->get(route('pesanan.rating.form', $pesanan->id_pesanan))
            ->assertRedirect(route('login'));

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [])
            ->assertRedirect(route('login'));
    });

    test('pesanan yang belum selesai ditolak', function () {
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

    test('pesanan milik user lain ditolak', function () {
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

    test('simpan rating sukses untuk pesanan selesai', function () {
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
            'order_ref' => $pesanan->id_pesanan,
            'rating' => 5,
            'ulasan' => 'Hasil wrapping sangat memuaskan!',
        ]);
    });

    test('hanya 1 rating per layanan per pesanan (update, bukan duplikat)', function () {
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

        $this->assertSame(1, Rating::where('id_pesanan', $pesanan->id_pesanan)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());

        $this->assertDatabaseHas('ratings', [
            'id_pesanan' => $pesanan->id_pesanan,
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Ulasan setelah update',
        ]);
    });

    test('validasi: rating wajib 1-5 dan layanan harus ada pada pesanan', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        // rating di luar range
        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 6,
        ])->assertSessionHasErrors('rating');

        // layanan tidak ada di pesanan
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

    test('user yang sama boleh rate layanan yang sama di pesanan berbeda', function () {
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

        $this->assertSame(2, Rating::where('id_user', $this->actor->id)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());
    });
});

/* [DISABLED] Alur 2 - rating layanan tanpa pesanan (dropdown layanan).
 * Fitur non-aktif; rute & endpoint dikomentari. Blok dibiarkan sebagai referensi.
 * (Versi aktif test "user yang sama boleh rate..." sudah dipindah ke describe Alur 1.)
describe('Alur 2 - rating layanan tanpa pesanan', function () {
    test('guest tidak bisa mengakses form layanan', function () {
        $this->get(route('rating.layanan.form'))->assertRedirect(route('login'));
        $this->post(route('rating.layanan.store'), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ])->assertRedirect(route('login'));
    });

    test('simpan rating layanan sukses', function () {
        ($this->actAs)();

        $this->post(route('rating.layanan.store'), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Suka sekali dengan hasilnya!',
        ])->assertSessionHas('toast_success')->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('ratings', [
            'id_user' => $this->actor->id,
            'id_pesanan' => null,
            'id_layanan' => $this->layanan->id_layanan,
            'order_ref' => 0,
            'rating' => 5,
            'is_tampil' => 1,
        ]);
    });

    test('1x per layanan per akun (update, bukan duplikat)', function () {
        ($this->actAs)();

        $this->post(route('rating.layanan.store'), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 3,
        ])->assertSessionHas('toast_success')->assertRedirect(route('dashboard'));

        $this->post(route('rating.layanan.store'), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 4,
        ])->assertSessionHas('toast_success')->assertRedirect(route('dashboard'));

        $this->assertSame(1, Rating::where('id_user', $this->actor->id)
            ->whereNull('id_pesanan')
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());

        $this->assertDatabaseHas('ratings', [
            'id_user' => $this->actor->id,
            'id_layanan' => $this->layanan->id_layanan,
            'order_ref' => 0,
            'rating' => 4,
        ]);
    });

    test('user yang sama boleh rate layanan yang sama di pesanan berbeda', function () {
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

        $this->assertSame(2, Rating::where('id_user', $this->actor->id)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());
    });
});
*/

describe('Testimoni publik', function () {
    test('halaman /testimoni menampilkan hanya rating is_tampil = true', function () {
        ($this->actAs)();

        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Ulasan tampil',
        ]);

        $hidden = Rating::create([
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

    test('ringkasan rata-rata dihitung dari semua rating tampil', function () {
        ($this->actAs)();
        $pesanan = createSelesaiPesanan($this->actor, $this->layanan);

        $this->post(route('pesanan.rating.store', $pesanan->id_pesanan), [
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
        ]);

        // [DISABLED Alur 2] Rating lama tanpa pesanan tetap diperhitungkan ringkasan
        // (data lama tetap tampil publik). Dibuat langsung via model, bukan route.
        Rating::create([
            'id_user' => $this->actor->id,
            'id_layanan' => $this->layanan->id_layanan,
            'order_ref' => 0,
            'rating' => 4,
        ]);

        $this->get(route('testimoni.index'))
            ->assertOk()
            ->assertSee('4,5', false); // (5+4) / 2, pakai format Indonesia (koma)
    });
});

describe('Alur Booking - rating via booking', function () {
    test('guest tidak bisa menyimpan rating booking', function () {
        $booking = createCompletedBooking($this->actor, $this->layanan);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
        ])->assertRedirect(route('login'));
    });

    test('booking yang belum selesai tidak bisa dirating', function () {
        ($this->actAs)();

        $booking = Booking::create([
            'booking_code' => 'BKG-PEND-' . strtoupper(uniqid()),
            'user_id' => $this->actor->id,
            'customer_name' => $this->actor->name,
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->toDateString(),
            'payment_type' => 'lunas',
            'vehicle_name' => 'Honda Civic',
            'status' => 'pending',
        ]);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
        ])->assertForbidden();

        $this->assertDatabaseMissing('ratings', ['booking_id' => $booking->id]);
    });

    test('booking milik user lain ditolak', function () {
        $other = User::factory()->create();
        $booking = createCompletedBooking($other, $this->layanan);

        ($this->actAs)();

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
        ])->assertNotFound();
    });

    test('simpan rating booking sukses', function () {
        ($this->actAs)();
        $booking = createCompletedBooking($this->actor, $this->layanan);

        $response = $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
            'ulasan' => 'Hasil wrapping mobil sangat rapi dan mulus!',
        ]);

        $response->assertSessionHas('toast_success');

        $this->assertDatabaseHas('ratings', [
            'id_user' => $this->actor->id,
            'booking_id' => $booking->id,
            'id_layanan' => $this->layanan->id_layanan,
            'rating' => 5,
            'ulasan' => 'Hasil wrapping mobil sangat rapi dan mulus!',
        ]);
    });

    test('update rating booking jika sudah pernah diulas', function () {
        ($this->actAs)();
        $booking = createCompletedBooking($this->actor, $this->layanan);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 4,
            'ulasan' => 'Ulasan awal',
        ]);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
            'ulasan' => 'Ulasan diubah menjadi 5 bintang',
        ]);

        $this->assertSame(1, Rating::where('booking_id', $booking->id)->count());
        $this->assertDatabaseHas('ratings', [
            'booking_id' => $booking->id,
            'rating' => 5,
            'ulasan' => 'Ulasan diubah menjadi 5 bintang',
        ]);
    });

    test('quick-submit endpoint sukses untuk booking via ajax json', function () {
        ($this->actAs)();
        $booking = createCompletedBooking($this->actor, $this->layanan);

        $response = $this->postJson(route('rating.quick-submit'), [
            'booking_id' => $booking->id,
            'rating' => 5,
            'ulasan' => 'Submit cepat dari modal',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('ratings', [
            'booking_id' => $booking->id,
            'rating' => 5,
            'ulasan' => 'Submit cepat dari modal',
        ]);
    });
});

