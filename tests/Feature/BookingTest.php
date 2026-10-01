<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingTest extends TestCase
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

    /**
     * Test Case:
     * Accessing booking creation page renders successfully with Date Strip.
     */
    public function test_verified_user_can_access_booking_create_page()
    {
        $response = $this->actingAs($this->verifiedUser)->get(route('booking.create'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Booking Pengerjaan');
        $response->assertSee('Pilih Cepat Jadwal');
        $response->assertSee('14 Hari ke Depan');
    }

    /**
     * Test Case 1 (Positive Case):
     * Successful booking creation by a verified customer with valid inputs.
     */
    public function test_verified_user_can_create_booking_successfully()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Premium',
            'deskripsi' => 'Layanan pembungkusan bodi mobil secara menyeluruh.',
            'harga' => 5000000,
            'tipe_layanan' => 'fix',
            'estimasi_waktu' => '2 Hari',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $layanan->id_layanan,
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
            'layanan_id' => $layanan->id_layanan,
            'vehicle_name' => 'Honda Civic Turbo 2023',
            'payment_type' => 'dp',
            'status' => 'pending',
        ]);

        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertNotNull($booking);
        $this->assertNotNull($booking->payment);
        $this->assertEquals(2500000, $booking->payment->amount);
    }

    /**
     * Test Case 2 (Negative Case):
     * Unverified email user is blocked from creating a booking (anti slot-hoarding).
     */
    public function test_unverified_user_cannot_create_booking()
    {
        $unverifiedUser = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Standard',
            'harga' => 3000000,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($unverifiedUser)->post(route('booking.store'), [
            'layanan_id' => $layanan->id_layanan,
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

    /**
     * Test Case 3 (Boundary Case 1):
     * Daily quota limit boundary - 4th booking succeeds (max quota), 5th booking fails with quota full exception.
     */
    public function test_daily_booking_quota_limit_boundary()
    {
        $targetDate = now()->addDays(3)->toDateString();

        $layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Quota Test',
            'harga' => 4000000,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        // Create 4 existing bookings for target date using separate verified users
        for ($i = 1; $i <= 4; $i++) {
            $user = User::factory()->create(['email_verified_at' => now()]);
            Booking::create([
                'booking_code' => 'BKG-TEST-00' . $i,
                'user_id' => $user->id,
                'layanan_id' => $layanan->id_layanan,
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
            'layanan_id' => $layanan->id_layanan,
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
            'layanan_id' => $layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Ke-6',
            'proof_file' => $file6,
        ]);

        $response6->assertSessionHas('toast_error');
        $this->assertDatabaseCount('bookings', 5);
    }

    /**
     * Test Case 4 (Boundary Case 2):
     * Payment amount boundary calculation - DP 50% vs Lunas 100% amounts.
     */
    public function test_payment_scheme_amount_boundary_calculation()
    {
        $bookingService = app(BookingService::class);

        $layanan = Layanan::create([
            'nama_layanan' => 'Layanan Harga Pas',
            'harga' => 5000000,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        // Service with price Rp 5.000.000
        $dpAmount = $bookingService->calculateDpAmount($layanan);
        $this->assertEquals(2500000, $dpAmount);

        // Service with odd price Rp 1.775.500 -> DP should round correctly
        $layananOdd = Layanan::create([
            'nama_layanan' => 'Wrapping Kap Mesin',
            'harga' => 1775500,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        $dpOddAmount = $bookingService->calculateDpAmount($layananOdd);
        $this->assertEquals(887750, $dpOddAmount);
    }

    /**
     * Test Case 5 (Negative Case):
     * Duplicate booking attempt by the same user on the same date is blocked.
     */
    public function test_user_cannot_create_duplicate_booking_on_same_date()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $targetDate = now()->addDays(2)->toDateString();

        $file1 = UploadedFile::fake()->create('bukti1.jpg', 500, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('bukti2.jpg', 500, 'image/jpeg');

        // First booking -> SUCCEEDS
        $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => $targetDate,
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Mobil Pertama',
            'proof_file' => $file1,
        ])->assertSessionHas('toast_success');

        // Second booking on SAME date -> FAILS with Duplicate Exception
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

    /**
     * Test Case 6 (Negative Case):
     * Invalid file type upload is rejected by validation rules.
     */
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

    /**
     * Test Case 7 (Negative Case):
     * Booking on a past date is rejected by date validation (after_or_equal:today).
     */
    public function test_booking_on_past_date_is_rejected()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $file = UploadedFile::fake()->create('bukti.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->subDay()->toDateString(), // Kemarin
            'payment_type' => 'dp',
            'payment_method' => 'transfer_bank',
            'vehicle_name' => 'Toyota Yaris',
            'proof_file' => $file,
        ]);

        $response->assertSessionHasErrors(['booking_date']);
        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Test Case 8 (Positive Case):
     * User can cancel their own booking when status is pending.
     */
    public function test_user_can_cancel_pending_booking()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $booking = Booking::create([
            'booking_code' => 'BKG-CANCEL-TEST',
            'user_id' => $user->id,
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(3)->toDateString(),
            'payment_type' => 'dp',
            'vehicle_name' => 'Honda CRV',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post(route('booking.cancel', $booking->id), [
            'alasan' => 'Rencana berubah',
        ]);

        $response->assertSessionHas('toast_success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }

    /**
     * Test Case 9:
     * User can view their booking show page with the progress stepper without any operand TypeErrors.
     */
    public function test_user_can_view_booking_show_page_with_stepper()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $booking = Booking::create([
            'booking_code' => 'BKG-SHOW-TEST',
            'user_id' => $user->id,
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'vehicle_name' => 'Mazda CX-5',
            'vehicle_color' => 'Soul Red',
            'pelanggan_nama' => $user->name,
            'pelanggan_phone' => '081234567890',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->get(route('booking.show', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('BKG-SHOW-TEST');
        $response->assertSee('Mazda CX-5');
        $response->assertSee('Progress Status Booking');
    }

    /**
     * Test Case 10 (Security):
     * User cannot view another user's booking details.
     */
    public function test_user_cannot_view_another_users_booking()
    {
        $userA = User::factory()->create(['email_verified_at' => now()]);
        $userB = User::factory()->create(['email_verified_at' => now()]);

        $booking = Booking::create([
            'booking_code' => 'BKG-ISOLATION-TEST',
            'user_id' => $userA->id,
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'dp',
            'vehicle_name' => 'Honda Civic',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($userB)->get(route('booking.show', $booking->id));

        $response->assertStatus(404);
    }

    /**
     * Test Case 11 (Invoice):
     * User can view and download official booking invoice when status is completed/approved.
     */
    public function test_user_can_view_booking_invoice()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $booking = Booking::create([
            'booking_code' => 'BKG-INV-TEST-001',
            'user_id'      => $user->id,
            'customer_name'=> $user->name,
            'layanan_id'   => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'lunas',
            'vehicle_name' => 'Toyota Alphard',
            'status'       => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('booking.invoice', $booking->id));

        // Response must be a successful PDF download
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        // DomPDF outputs filename without surrounding quotes
        $this->assertStringContainsString(
            'Invoice-BKG-INV-TEST-001.pdf',
            $response->headers->get('Content-Disposition')
        );
    }

    /**
     * Test Case 12 (Security):
     * User cannot view another user's booking invoice.
     */
    public function test_user_cannot_view_another_users_booking_invoice()
    {
        $userA = User::factory()->create(['email_verified_at' => now()]);
        $userB = User::factory()->create(['email_verified_at' => now()]);

        $booking = Booking::create([
            'booking_code' => 'BKG-INV-SECRET',
            'user_id' => $userA->id,
            'customer_name' => $userA->name,
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'lunas',
            'vehicle_name' => 'BMW 330i',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($userB)->get(route('booking.invoice', $booking->id));

        $response->assertStatus(404);
    }
}
