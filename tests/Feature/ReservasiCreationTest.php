<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReservasiCreationTest extends TestCase
{
    use RefreshDatabase;

    protected User $verifiedUser;
    protected User $unverifiedUser;
    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        Storage::fake('public');
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->verifiedUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->unverifiedUser = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Premium',
            'deskripsi' => 'Layanan pembungkusan bodi mobil secara menyeluruh.',
            'harga' => 5000000,
            'tipe_layanan' => 'fix',
            'estimasi_waktu' => '2 Hari',
            'status' => 'aktif',
        ]);
    }

    public function test_verified_user_can_access_booking_create_page()
    {
        $response = $this->actingAs($this->verifiedUser)->get(route('booking.create'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Booking Pengerjaan');
        $response->assertSee('Pilih Cepat Jadwal');
        $response->assertSee('14 Hari ke Depan');
    }

    public function test_verified_user_can_create_booking_successfully()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Honda Civic Turbo 2023',
            'vehicle_color' => 'Hitam Metalik',
            'vehicle_license' => 'B 1234 ABC',
            'notes' => 'Tolong pengerjaan dibersihkan terlebih dahulu.',
            'proof_file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('toast_success');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'layanan_id' => $this->layanan->id_layanan,
            'vehicle_name' => 'Honda Civic Turbo 2023',
            'payment_type' => 'dp',
            'status' => 'pending',
        ]);

        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertNotNull($booking);
        $this->assertNotNull($booking->payment);
        $this->assertEquals(2500000, $booking->payment->amount);
    }

    public function test_unverified_user_cannot_create_booking()
    {
        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->unverifiedUser)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Toyota Fortuner',
            'proof_file' => $file,
        ]);

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('toast_warning');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_daily_booking_quota_limit_boundary()
    {
        $targetDate = now()->addDays(3)->toDateString();

        for ($i = 1; $i <= 4; $i++) {
            $user = User::factory()->create(['email_verified_at' => now()]);
            Booking::create([
                'booking_code' => 'BKG-TEST-00' . $i,
                'user_id' => $user->id,
                'layanan_id' => $this->layanan->id_layanan,
                'booking_date' => $targetDate,
                'payment_type' => 'dp',
                'vehicle_name' => 'Mobil ' . $i,
                'status' => 'pending',
            ]);
        }

        // 5th booking (Boundary Max Limit = 5/5) -> should SUCCEED
        $user5 = User::factory()->create(['email_verified_at' => now()]);
        $file5 = UploadedFile::fake()->create('bukti_5.jpg', 500, 'image/jpeg');

        $response5 = $this->actingAs($user5)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Ke-5',
            'proof_file' => $file5,
        ]);

        $response5->assertRedirect();
        $response5->assertSessionHas('toast_success');
        $this->assertDatabaseCount('bookings', 5);

        // 6th booking (Over Boundary) -> should FAIL with error quota full
        $user6 = User::factory()->create(['email_verified_at' => now()]);
        $file6 = UploadedFile::fake()->create('bukti_6.jpg', 500, 'image/jpeg');

        $response6 = $this->actingAs($user6)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Ke-6',
            'proof_file' => $file6,
        ]);

        $response6->assertSessionHas('toast_error');
        $this->assertDatabaseCount('bookings', 5);
    }

    public function test_payment_scheme_amount_boundary_calculation()
    {
        $bookingService = app(BookingService::class);

        $dpAmount = $bookingService->calculateDpAmount($this->layanan);
        $this->assertEquals(2500000, $dpAmount);

        $layananOdd = Layanan::create([
            'nama_layanan' => 'Wrapping Kap Mesin',
            'harga' => 1775500,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        $dpOddAmount = $bookingService->calculateDpAmount($layananOdd);
        $this->assertEquals(887750, $dpOddAmount);
    }

    public function test_user_cannot_create_duplicate_booking_on_same_date()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $targetDate = now()->addDays(2)->toDateString();

        $file1 = UploadedFile::fake()->create('bukti1.jpg', 500, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('bukti2.jpg', 500, 'image/jpeg');

        $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Pertama',
            'proof_file' => $file1,
        ])->assertSessionHas('toast_success');

        $response2 = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Kedua',
            'proof_file' => $file2,
        ]);

        $response2->assertSessionHas('toast_error');
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_invalid_file_type_is_rejected()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $fileExe = UploadedFile::fake()->create('script.exe', 500, 'application/x-msdownload');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Honda Jazz',
            'proof_file' => $fileExe,
        ]);

        $response->assertSessionHasErrors(['proof_file']);
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_on_past_date_is_rejected()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $file = UploadedFile::fake()->create('bukti.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->subDay()->toDateString(),
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Toyota Yaris',
            'proof_file' => $file,
        ]);

        $response->assertSessionHasErrors(['booking_date']);
        $this->assertDatabaseCount('bookings', 0);
    }
}
