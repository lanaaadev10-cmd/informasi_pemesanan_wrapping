<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Layanan;
use App\Models\Rating;
use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\Addon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pengujian integrasi alur end-to-end reservasi wrapping:
 * Formulir Pelanggan -> Validasi -> Service / Database -> Admin Filament -> Dashboard Pelanggan -> Testimoni Publik
 */
class IntegrasiReservasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Layanan $layanan;
    protected VehicleBrand $brand;
    protected VehicleModel $model;
    protected Addon $addon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        // Buat User Admin
        $this->admin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'admin.audit@dantie.id',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        // Buat User Customer
        $this->customer = User::factory()->create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi.audit@gmail.com',
            'email_verified_at' => now(),
            'phone' => '081299998888',
        ]);
        $this->customer->assignRole('user');

        // Master Data
        $this->layanan = Layanan::create([
            'nama_layanan' => 'Full Body Wrap Matte Grey',
            'deskripsi' => 'Wrapping bodi penuh dengan bahan vinyl matte premium.',
            'harga' => 5000000,
            'tipe_layanan' => 'fix',
            'jenis_layanan' => 'paket',
            'is_active' => true,
        ]);

        $this->brand = VehicleBrand::create([
            'name' => 'Honda',
            'vehicle_type' => 'mobil',
            'is_active' => true,
        ]);

        $this->model = VehicleModel::create([
            'brand_id' => $this->brand->id,
            'name' => 'Civic Turbo',
            'vehicle_size' => 'medium',
            'is_active' => true,
        ]);

        $this->addon = Addon::create([
            'name' => 'Coating Spion & Handle',
            'category' => 'mobil',
            'price' => 350000,
            'is_active' => true,
        ]);
    }

    /**
     * 1. Alur Lengkap: Booking Baru -> Validasi Kuota -> DB -> Admin Filament -> Selesai -> Rating -> Tampil Publik
     */
    public function test_full_lifecycle_booking_to_admin_to_rating(): void
    {
        Storage::fake('public');

        $bookingDate = now()->addDays(3)->toDateString();
        $fakeProof = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600);

        // LANGKAH 1: Customer submit form booking melalui endpoint POST /booking
        $payload = [
            'layanan_id'       => $this->layanan->id_layanan,
            'booking_date'     => $bookingDate,
            'booking_time'     => '09:00',
            'payment_type'     => 'dp',
            'payment_method'   => 'transfer_bank',
            'customer_name'    => 'Budi Pelanggan',
            'customer_phone'   => '081299998888',
            'customer_email'   => 'budi.audit@gmail.com',
            'brand_id'         => $this->brand->id,
            'model_id'         => $this->model->id,
            'vehicle_name'     => 'Honda Civic Turbo',
            'vehicle_color'    => 'Putih Metalik',
            'vehicle_license'  => 'B 1234 BDI',
            'addons'           => [$this->addon->id],
            'bukti_pembayaran' => $fakeProof,
        ];

        $responseBooking = $this->actingAs($this->customer)
            ->post(route('booking.store'), $payload);

        // Harus berhasil dan redirect ke invoice/detail
        $this->assertTrue(in_array($responseBooking->status(), [200, 302]));

        // LANGKAH 2: Verifikasi data tersimpan di Database dengan relasi lengkap
        $this->assertDatabaseHas('bookings', [
            'user_id'         => $this->customer->id,
            'customer_name'   => 'Budi Pelanggan',
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => $bookingDate,
            'vehicle_license' => 'B 1234 BDI',
        ]);

        $booking = Booking::where('user_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($booking);
        $this->assertCount(1, $booking->addons);
        $this->assertNotNull($booking->payment);
        $this->assertEquals('pending', $booking->payment->status);

        // LANGKAH 3: Customer dapat melihat booking di Hub Transaksi (/transaksi)
        $responseTransaksi = $this->actingAs($this->customer)->get(route('transaksi.index'));
        $responseTransaksi->assertStatus(200);
        $responseTransaksi->assertSee($booking->booking_code);
        $responseTransaksi->assertSee('Full Body Wrap Matte Grey');

        // LANGKAH 4: Admin dapat melihat booking di Panel Admin Filament (/admin/booking)
        $responseAdminTable = $this->actingAs($this->admin)->get('/admin/booking');
        $responseAdminTable->assertStatus(200);
        $responseAdminTable->assertSee($booking->booking_code);

        // LANGKAH 5: Admin memverifikasi pembayaran & menyelesaikan pengerjaan (Update status)
        $booking->payment->update(['status' => 'verified', 'verified_at' => now()]);
        $booking->update(['status' => BookingStatus::COMPLETED]);

        // LANGKAH 6: Customer melihat status 'Selesai' di Hub Transaksi & Reminder Ulasan di Dashboard
        $responseCustomerDash = $this->actingAs($this->customer)->get(route('dashboard'));
        $responseCustomerDash->assertStatus(200);
        $responseCustomerDash->assertSee('Beri Ulasan');

        // LANGKAH 7: Customer submit ulasan & rating 5 bintang
        $ratingPayload = [
            'booking_id' => $booking->id,
            'rating'     => 5,
            'ulasan'     => 'Pengerjaan sangat rapi, detailing sudut-sudut stiker sempurna!',
        ];

        $responseRating = $this->actingAs($this->customer)
            ->postJson(route('rating.quick-submit'), $ratingPayload);

        $responseRating->assertStatus(200);
        $responseRating->assertJson(['success' => true]);

        // LANGKAH 8: Verifikasi rating tersimpan di database
        $this->assertDatabaseHas('ratings', [
            'id_user'    => $this->customer->id,
            'booking_id' => $booking->id,
            'rating'     => 5,
            'is_tampil'  => true,
        ]);

        // LANGKAH 9: Ulasan muncul di halaman publik /testimoni
        $responseTestimoni = $this->get(route('testimoni.index'));
        $responseTestimoni->assertStatus(200);
        $responseTestimoni->assertSee('Pengerjaan sangat rapi');
    }

    /**
     * 2. Isolasi Keamanan: Customer B tidak dapat mengakses booking atau tagihan Customer A
     */
    public function test_customer_isolation_and_idor_prevention(): void
    {
        $otherCustomer = User::factory()->create([
            'email' => 'other@gmail.com',
            'email_verified_at' => now(),
        ]);
        $otherCustomer->assignRole('user');

        $booking = Booking::create([
            'booking_code'    => 'BK-ISOLATION-01',
            'user_id'         => $this->customer->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(4)->toDateString(),
            'booking_time'    => '10:00',
            'customer_name'   => 'Budi Pelanggan',
            'customer_phone'  => '081299998888',
            'vehicle_name'    => 'Civic Turbo',
            'vehicle_license' => 'B 1234 BDI',
            'status'          => BookingStatus::PENDING,
        ]);

        // Customer B mencoba membuka detail booking Customer A -> Harus 403 atau 404
        $responseUnauthorized = $this->actingAs($otherCustomer)
            ->get(route('booking.show', $booking->id));

        $this->assertTrue(in_array($responseUnauthorized->status(), [403, 404, 302]));

        // Customer B tidak boleh melihat kode booking Customer A di /transaksi
        $responseOtherHub = $this->actingAs($otherCustomer)->get(route('transaksi.index'));
        $responseOtherHub->assertStatus(200);
        $responseOtherHub->assertDontSee('BK-ISOLATION-01');
    }

    /**
     * 3. Integritas Kuota: Maksimal 5 slot pengerjaan per hari tidak dapat ditembus
     */
    public function test_daily_quota_boundary_is_strictly_enforced(): void
    {
        $targetDate = now()->addDays(5)->toDateString();

        // Buat 5 booking aktif pada tanggal yang sama
        for ($i = 1; $i <= 5; $i++) {
            Booking::create([
                'booking_code'    => "BK-SLOT-FULL-0{$i}",
                'user_id'         => $this->customer->id,
                'layanan_id'      => $this->layanan->id_layanan,
                'booking_date'    => $targetDate,
                'booking_time'    => "0{$i}:00",
                'customer_name'   => "Customer {$i}",
                'customer_phone'  => "08120000000{$i}",
                'vehicle_name'    => 'Mobil Test',
                'vehicle_license' => "B {$i} TEST",
                'status'          => BookingStatus::CONFIRMED,
            ]);
        }

        // Cek kuota publik via API
        $responseQuota = $this->getJson("/api/booking/quota/{$targetDate}");
        $responseQuota->assertStatus(200);
        $responseQuota->assertJson([
            'quota' => [
                'available' => 0,
                'is_full'   => true,
            ],
        ]);

        // Coba masukkan booking ke-6 oleh user biasa -> Harus ditolak (exception/redirect dengan pesan error)
        $payload6 = [
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => $targetDate,
            'booking_time'    => '14:00',
            'payment_type'    => 'dp',
            'customer_name'   => 'Pemesan Ke Enam',
            'customer_phone'  => '081288887777',
            'brand_id'        => $this->brand->id,
            'model_id'        => $this->model->id,
            'vehicle_name'    => 'Civic',
            'vehicle_license' => 'B 9999 OVR',
        ];

        $responseAttempt = $this->actingAs($this->customer)->post(route('booking.store'), $payload6);
        $responseAttempt->assertSessionHas('toast_error');

        // Pastikan record ke-6 tidak tersimpan di database
        $this->assertDatabaseMissing('bookings', [
            'vehicle_license' => 'B 9999 OVR',
        ]);
    }
}
