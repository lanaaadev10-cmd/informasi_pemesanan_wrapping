<?php

use App\Models\Layanan;
use App\Models\Penilaian;
use App\Models\Reservasi;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
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

if (!function_exists('createCompletedBookingForTest')) {
    function createCompletedBookingForTest(User $user, Layanan $layanan): Reservasi
    {
        return Reservasi::create([
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
}

describe('Alur Reservasi - Penilaian via Booking Slot Workshop', function () {
    test('tamu tanpa autentikasi tidak dapat menyimpan ulasan reservasi', function () {
        $booking = createCompletedBookingForTest($this->actor, $this->layanan);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
        ])->assertRedirect(route('login'));
    });

    test('reservasi yang belum berstatus selesai tidak dapat diulas', function () {
        ($this->actAs)();

        $booking = Reservasi::create([
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

    test('reservasi milik pelanggan lain ditolak untuk diulas', function () {
        $other = User::factory()->create();
        $booking = createCompletedBookingForTest($other, $this->layanan);

        ($this->actAs)();

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
        ])->assertNotFound();
    });

    test('simpan ulasan reservasi berhasil', function () {
        ($this->actAs)();
        $booking = createCompletedBookingForTest($this->actor, $this->layanan);

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

    test('pembaruan ulasan reservasi jika sudah pernah diulas sebelumnya', function () {
        ($this->actAs)();
        $booking = createCompletedBookingForTest($this->actor, $this->layanan);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 4,
            'ulasan' => 'Ulasan awal',
        ]);

        $this->post(route('booking.rating.store', $booking->id), [
            'rating' => 5,
            'ulasan' => 'Ulasan diubah menjadi 5 bintang',
        ]);

        $this->assertSame(1, Penilaian::where('booking_id', $booking->id)->count());
        $this->assertDatabaseHas('ratings', [
            'booking_id' => $booking->id,
            'rating' => 5,
            'ulasan' => 'Ulasan diubah menjadi 5 bintang',
        ]);
    });

    test('endpoint quick-submit berhasil untuk penilaian reservasi via ajax json', function () {
        ($this->actAs)();
        $booking = createCompletedBookingForTest($this->actor, $this->layanan);

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

    test('pelanggan yang sama boleh memberikan ulasan pada reservasi berbeda dengan layanan sama', function () {
        ($this->actAs)();

        $bookingA = createCompletedBookingForTest($this->actor, $this->layanan);
        $bookingB = createCompletedBookingForTest($this->actor, $this->layanan);

        $this->post(route('booking.rating.store', $bookingA->id), [
            'rating' => 5,
            'ulasan' => 'Booking pertama sangat memuaskan',
        ])->assertSessionHas('toast_success');

        $this->post(route('booking.rating.store', $bookingB->id), [
            'rating' => 4,
            'ulasan' => 'Booking kedua juga bagus',
        ])->assertSessionHas('toast_success');

        $this->assertSame(2, Penilaian::where('id_user', $this->actor->id)
            ->where('id_layanan', $this->layanan->id_layanan)
            ->count());

        $this->assertDatabaseHas('ratings', [
            'booking_id' => $bookingA->id,
            'rating' => 5,
            'ulasan' => 'Booking pertama sangat memuaskan',
        ]);

        $this->assertDatabaseHas('ratings', [
            'booking_id' => $bookingB->id,
            'rating' => 4,
            'ulasan' => 'Booking kedua juga bagus',
        ]);
    });
});
