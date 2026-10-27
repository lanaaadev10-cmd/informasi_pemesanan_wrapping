<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Helpers\WhatsAppHelper;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurPemesananJasaCustomTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected User $otherCustomer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->admin = User::factory()->create([
            'name'              => 'Admin Bengkel',
            'email'             => 'admin.bengkel@dantie.id',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        $this->customer = User::factory()->create([
            'name'              => 'Ahmad Dani',
            'email'             => 'ahmad.dani@example.com',
            'phone'             => '081234567890',
            'email_verified_at' => now(),
        ]);
        $this->customer->assignRole('user');

        $this->otherCustomer = User::factory()->create([
            'name'              => 'Bukan Pemilik',
            'email'             => 'other.user@example.com',
            'phone'             => '089999999999',
            'email_verified_at' => now(),
        ]);
        $this->otherCustomer->assignRole('user');

        // Seed regular package
        Layanan::create([
            'nama_layanan'   => 'Paket Full Wrap Dantie Glossy',
            'jenis_layanan'  => 'paket',
            'tipe_layanan'   => 'fix',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'wrapping',
            'harga'          => 3500000,
            'estimasi_waktu' => '2-3 Hari',
            'is_active'      => true,
        ]);

        // Seed jasa pasang
        Layanan::create([
            'nama_layanan'   => 'Jasa Pasang Decal Mobil (Bawa Bahan Sendiri)',
            'jenis_layanan'  => 'jasa_pasang',
            'tipe_layanan'   => 'fix',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'jasa-pasang',
            'harga'          => 1200000,
            'estimasi_waktu' => '1-2 Hari',
            'is_active'      => true,
        ]);
    }

    /**
     * 1. Verifikasi WhatsApp Helper menghasilkan link resmi & pesan prefilled sesuai spesifikasi.
     */
    public function test_whatsapp_helper_generates_exact_prefilled_messages_and_encoded_urls(): void
    {
        $expectedJasaPesan = "Halo Admin, saya ingin berkonsultasi dan melakukan reservasi jasa wrapping kendaraan. Mohon informasi lebih lanjut mengenai kebutuhan dan proses pemesanannya.";
        $expectedCustomPesan = "Halo Admin, saya ingin berkonsultasi mengenai wrapping custom kendaraan. Saya ingin menjelaskan kebutuhan desain dan spesifikasi terlebih dahulu.";

        $jasaUrl = WhatsAppHelper::getJasaUrl();
        $customUrl = WhatsAppHelper::getCustomUrl();

        $this->assertStringContainsString('whatsapp.com', $jasaUrl);
        $this->assertStringContainsString('text=' . rawurlencode($expectedJasaPesan), $jasaUrl);

        $this->assertStringContainsString('whatsapp.com', $customUrl);
        $this->assertStringContainsString('text=' . rawurlencode($expectedCustomPesan), $customUrl);
    }

    /**
     * 2. Verifikasi halaman publik (katalog & layanan):
     *    - Tombol Jasa dan Custom mengarah langsung ke WhatsApp resmi
     *    - Tidak ada daftar paket, rincian harga paket, atau keranjang/checkout untuk Jasa dan Custom
     */
    public function test_catalog_pages_direct_jasa_and_custom_to_whatsapp_without_cart_checkout(): void
    {
        // 1. Halaman Layanan
        $responseLayanan = $this->get(route('layanan'));
        $responseLayanan->assertStatus(200);
        $responseLayanan->assertSee(e(WhatsAppHelper::getJasaUrl()), false);
        $responseLayanan->assertSee(e(WhatsAppHelper::getCustomUrl()), false);

        // 2. Halaman Katalog User
        $responseKatalog = $this->actingAs($this->customer)->get(route('katalog.user'));
        $responseKatalog->assertStatus(200);
        $responseKatalog->assertSee(e(WhatsAppHelper::getJasaUrl()), false);
        $responseKatalog->assertSee(e(WhatsAppHelper::getCustomUrl()), false);

        // 3. Halaman Dashboard Pelanggan
        $responseDashboard = $this->actingAs($this->customer)->get(route('dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee(e(WhatsAppHelper::getJasaUrl()), false);
        $responseDashboard->assertSee(e(WhatsAppHelper::getCustomUrl()), false);
    }

    /**
     * 3. Verifikasi Admin dapat membuat pesanan JASA setelah kesepakatan WhatsApp:
     *    - Menggunakan user_id akun pembeli
     *    - Tanpa mengharuskan memilih paket katalog (layanan_id = null)
     *    - Memasukkan detail kebutuhan, kendaraan, harga yang disepakati, jadwal, catatan
     */
    public function test_admin_can_create_jasa_order_for_registered_buyer_without_catalog_package(): void
    {
        $bookingService = app(BookingService::class);
        $bookingDate = Carbon::now()->addDays(3)->toDateString();

        $orderData = [
            'tipe_pemesanan'      => 'jasa',
            'custom_project_name' => 'Jasa Pasang Stiker Brio Full Body (Bahan TeckWrap Bawa Sendiri)',
            'quotation_amount'    => 1800000,
            'quotation_notes'     => 'Deal WA: Pelanggan bawa bahan sendiri 1 roll, estimasi 2 hari.',
            'quotation_status'    => 'accepted',
            'user_id'             => $this->customer->id,
            'customer_name'       => $this->customer->name,
            'customer_phone'      => $this->customer->phone,
            'customer_email'      => $this->customer->email,
            'booking_date'        => $bookingDate,
            'booking_time'        => '09:00',
            'vehicle_name'        => 'Honda Brio RS 2022',
            'vehicle_color'       => 'Kuning',
            'vehicle_license'     => 'B 1234 JSA',
            'notes'               => 'Pastikan bodi mobil bebas jamur sebelum pasang bahan bawaan.',
            'status'              => BookingStatus::CONFIRMED->value,
            'payment_type'        => 'dp',
        ];

        $booking = $bookingService->createManualBooking($orderData, true);

        // 1. Verifikasi integritas data di database
        $this->assertNotNull($booking);
        $this->assertNull($booking->layanan_id);
        $this->assertEquals($this->customer->id, $booking->user_id);
        $this->assertEquals('Jasa Pasang Stiker Brio Full Body (Bahan TeckWrap Bawa Sendiri)', $booking->service_name_snapshot);
        $this->assertEquals(1800000, (float) $booking->grand_total_snapshot);
        $this->assertEquals(900000, (float) $booking->dp_amount_snapshot);
        $this->assertEquals('Honda Brio RS 2022', $booking->vehicle_name);
        $this->assertEquals('B 1234 JSA', $booking->vehicle_license);
        $this->assertEquals('offline', $booking->source);

        // 2. Verifikasi pesanan langsung muncul pada dashboard pembeli terkait
        $responseDashboard = $this->actingAs($this->customer)->get(route('dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee($booking->booking_code);
        $responseDashboard->assertSee('Honda Brio RS 2022');

        // 3. Verifikasi pesanan muncul pada riwayat booking pembeli
        $responseIndex = $this->actingAs($this->customer)->get(route('booking.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee($booking->booking_code);
        $responseIndex->assertSee('Honda Brio RS 2022');

        // 4. Verifikasi pembeli dapat melihat rincian lengkap pesanan
        $responseShow = $this->actingAs($this->customer)->get(route('booking.show', $booking));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Jasa Pasang Stiker Brio Full Body (Bahan TeckWrap Bawa Sendiri)');
        $responseShow->assertSee(number_format(1800000, 0, ',', '.'));
        $responseShow->assertSee('B 1234 JSA');

        // 5. Verifikasi invoice pesanan dapat diunduh oleh pembeli
        $responseInvoice = $this->actingAs($this->customer)->get(route('booking.invoice', $booking));
        $responseInvoice->assertStatus(200);
        $responseInvoice->assertHeader('content-type', 'application/pdf');
    }

    /**
     * 4. Verifikasi Admin dapat membuat pesanan CUSTOM setelah kesepakatan WhatsApp:
     *    - Menggunakan user_id akun pembeli
     *    - Tanpa memilih paket katalog
     *    - Status tersinkronisasi ketika admin melakukan pembaruan
     */
    public function test_admin_can_create_custom_order_and_status_syncs_with_customer(): void
    {
        $bookingService = app(BookingService::class);
        $bookingDate = Carbon::now()->addDays(5)->toDateString();

        $orderData = [
            'tipe_pemesanan'      => 'custom',
            'custom_project_name' => 'Custom Livery Racing Cyberpunk 2077 Full Body',
            'quotation_amount'    => 6500000,
            'quotation_notes'     => 'Deal WA: Desain eksklusif livery cyberpunk holografik',
            'quotation_status'    => 'accepted',
            'user_id'             => $this->customer->id,
            'customer_name'       => $this->customer->name,
            'customer_phone'      => $this->customer->phone,
            'customer_email'      => $this->customer->email,
            'booking_date'        => $bookingDate,
            'booking_time'        => '10:00',
            'vehicle_name'        => 'Mazda 3 Hatchback',
            'vehicle_color'       => 'Soul Red Crystal',
            'vehicle_license'     => 'D 7777 CYB',
            'status'              => BookingStatus::CONFIRMED->value,
            'payment_type'        => 'lunas',
        ];

        $booking = $bookingService->createManualBooking($orderData, true);

        $this->assertNotNull($booking);
        $this->assertEquals($this->customer->id, $booking->user_id);
        $this->assertEquals('Custom Livery Racing Cyberpunk 2077 Full Body', $booking->service_name_snapshot);
        $this->assertEquals(6500000, (float) $booking->grand_total_snapshot);

        // Perubahan status oleh Admin: Dikerjakan (in_progress)
        $booking->update(['status' => BookingStatus::IN_PROGRESS]);

        // Cek akun pembeli melihat perubahan status
        $response = $this->actingAs($this->customer)->get(route('booking.show', $booking));
        $response->assertStatus(200);
        $response->assertSee(BookingStatus::IN_PROGRESS->label());

        // Perubahan status oleh Admin: Selesai (completed)
        $booking->update(['status' => BookingStatus::COMPLETED]);

        $responseCompleted = $this->actingAs($this->customer)->get(route('booking.show', $booking));
        $responseCompleted->assertStatus(200);
        $responseCompleted->assertSee(BookingStatus::COMPLETED->label());
    }

    /**
     * 5. Verifikasi Hak Akses & Isolasi Data (Keamanan):
     *    - Pembeli lain DITOLAK saat mencoba melihat pesanan milik pembeli ini (403 Forbidden)
     *    - Pembeli lain DITOLAK saat mencoba mengunduh invoice pesanan milik pembeli ini (403 Forbidden)
     */
    public function test_data_isolation_other_customers_cannot_access_booking_or_invoice(): void
    {
        $bookingService = app(BookingService::class);
        $bookingDate = Carbon::now()->addDays(4)->toDateString();

        $booking = $bookingService->createManualBooking([
            'tipe_pemesanan'      => 'custom',
            'custom_project_name' => 'Custom Wrap Rahasia Pelanggan',
            'quotation_amount'    => 5000000,
            'user_id'             => $this->customer->id,
            'customer_name'       => $this->customer->name,
            'customer_phone'      => $this->customer->phone,
            'booking_date'        => $bookingDate,
            'status'              => BookingStatus::CONFIRMED->value,
        ], true);

        // Pembeli lain mencoba akses detail booking (404 Not Found karena isolasi user_id)
        $responseOtherShow = $this->actingAs($this->otherCustomer)->get(route('booking.show', $booking));
        $responseOtherShow->assertStatus(404);

        // Pembeli lain mencoba unduh invoice (404 Not Found)
        $responseOtherInvoice = $this->actingAs($this->otherCustomer)->get(route('booking.invoice', $booking));
        $responseOtherInvoice->assertStatus(404);
    }
}
