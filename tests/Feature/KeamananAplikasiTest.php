<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Suite Pengujian Keamanan Input, Payload & Ketahanan Sistem
 * Memvalidasi sistem kebal terhadap:
 * 1. Malicious File Upload (Eksekusi Berkas Berbahaya & Ukuran Berlebih)
 * 2. Stored & Reflected XSS (Cross-Site Scripting)
 * 3. SQL Injection & Query Manipulation Resistance
 * 4. Business Logic & Financial Tampering (Manipulasi Nilai Negatif)
 * 5. Information Disclosure & Internal Endpoint Whitelist
 */
class KeamananAplikasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $victimUser;
    protected User $adminUser;
    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->victimUser = User::factory()->create([
            'name'              => 'Korban Legitim',
            'email'             => 'victim@secure-test.local',
            'email_verified_at' => now(),
        ]);
        $this->victimUser->assignRole('user');

        $this->adminUser = User::factory()->create([
            'name'              => 'Admin Sistem',
            'email'             => 'admin@dantie-security.local',
            'email_verified_at' => now(),
        ]);
        $this->adminUser->assignRole('admin');

        $this->layanan = Layanan::create([
            'nama_layanan'   => 'Security Audit Full Wrap',
            'jenis_layanan'  => 'paket',
            'tipe_layanan'   => 'fix',
            'kategori'       => 'mobil',
            'harga'          => 3500000,
            'estimasi_waktu' => '2 Hari',
            'is_active'      => true,
        ]);
    }

    public function test_file_upload_blocks_executable_php_and_scripts(): void
    {
        Storage::fake('public');

        $maliciousFile = UploadedFile::fake()->createWithContent('exploit.php', '<?php phpinfo(); ?>');

        $booking = Booking::create([
            'booking_code'    => 'BK-UPLOAD-TEST',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'booking_time'    => '09:00',
            'customer_name'   => 'Korban Legitim',
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic',
            'vehicle_license' => 'B 1000 VIC',
            'status'          => BookingStatus::AWAITING_PAYMENT,
        ]);

        $response = $this->actingAs($this->victimUser)
            ->post(route('booking.upload-bukti', $booking->id), [
                'proof_file' => $maliciousFile,
            ]);

        $response->assertSessionHasErrors(['proof_file']);
    }

    public function test_file_upload_blocks_files_exceeding_max_size(): void
    {
        Storage::fake('public');

        $oversizedFile = UploadedFile::fake()->create('huge_receipt.jpg', 10240, 'image/jpeg');

        $booking = Booking::create([
            'booking_code'    => 'BK-SIZE-TEST',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'booking_time'    => '09:00',
            'customer_name'   => 'Korban Legitim',
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic',
            'vehicle_license' => 'B 1000 VIC',
            'status'          => BookingStatus::AWAITING_PAYMENT,
        ]);

        $response = $this->actingAs($this->victimUser)
            ->post(route('booking.upload-bukti', $booking->id), [
                'proof_file' => $oversizedFile,
            ]);

        $response->assertSessionHasErrors(['proof_file']);
    }

    public function test_xss_payload_in_customer_input_is_escaped_in_blade(): void
    {
        $xssPayload = "<script>alert('XSS_ATTACK')</script>";

        $booking = Booking::create([
            'booking_code'    => 'BK-XSS-TEST',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'customer_name'   => $xssPayload,
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic ' . $xssPayload,
            'vehicle_license' => 'B 9999 XSS',
            'notes'           => $xssPayload,
            'status'          => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->victimUser)
            ->get(route('booking.show', $booking->id));

        $response->assertStatus(200);
        $response->assertDontSee($xssPayload, false);
        $response->assertSee('&lt;script&gt;alert(&#039;XSS_ATTACK&#039;)&lt;/script&gt;', false);
    }

    public function test_sql_injection_payload_in_filters_and_inputs_does_not_break_query(): void
    {
        $sqlPayload = "' OR '1'='1' -- ";

        $resTab = $this->actingAs($this->victimUser)
            ->get(route('transaksi.index', ['tab' => $sqlPayload, 'status' => $sqlPayload]));

        $resTab->assertStatus(200);
    }

    public function test_financial_tampering_negative_quantity_rejected_in_cart(): void
    {
        $resNegative = $this->actingAs($this->victimUser)
            ->post(route('keranjang.tambah'), [
                'id_paket' => $this->layanan->id_layanan,
                'jumlah'   => -5,
            ]);

        $resNegative->assertSessionHasErrors(['jumlah']);

        $resZero = $this->actingAs($this->victimUser)
            ->post(route('keranjang.tambah'), [
                'id_paket' => $this->layanan->id_layanan,
                'jumlah'   => 0,
            ]);

        $resZero->assertSessionHasErrors(['jumlah']);
    }

    public function test_business_logic_past_booking_date_strictly_rejected(): void
    {
        $resPastDate = $this->actingAs($this->victimUser)
            ->post(route('booking.store'), [
                'layanan_id'      => $this->layanan->id_layanan,
                'booking_date'    => '2020-01-01',
                'booking_time'    => '09:00',
                'payment_type'    => 'dp',
                'customer_name'   => 'Korban Legitim',
                'customer_phone'  => '081211112222',
                'vehicle_license' => 'B 1234 PAS',
            ]);

        $resPastDate->assertSessionHasErrors(['booking_date']);
    }

    public function test_metrics_endpoint_blocks_spoofed_external_ip(): void
    {
        $resExternal = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '198.51.100.24']);
        $resExternal->assertStatus(403);

        $resLocal = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        $resLocal->assertStatus(200);
    }
}
