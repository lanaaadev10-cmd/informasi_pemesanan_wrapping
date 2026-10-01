<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\FormPesanan;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiUnifiedTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->userA = User::factory()->create([
            'name' => 'Customer Alpha',
            'email' => 'alpha@test.com',
            'email_verified_at' => now(),
        ]);

        $this->userB = User::factory()->create([
            'name' => 'Customer Beta',
            'email' => 'beta@test.com',
            'email_verified_at' => now(),
        ]);

        $this->layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Satin Black',
            'deskripsi' => 'Wrapping premium doff satin warna hitam pekat.',
            'harga' => 6500000,
            'tipe_layanan' => 'fix',
            'estimasi_waktu' => '3 Hari',
        ]);
    }

    /**
     * 1. Guest diarahkan ke login saat mengakses /transaksi.
     */
    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get(route('transaksi.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * 2. User terautentikasi dapat melihat Hub Transaksi.
     */
    public function test_authenticated_user_can_view_transaksi_hub()
    {
        $response = $this->actingAs($this->userA)->get(route('transaksi.index'));

        $response->assertStatus(200);
        $response->assertSee('Aktivitas &amp; Transaksi', false);
        $response->assertSee('Jadwal Booking');
        $response->assertSee('Pesanan Layanan');
    }

    /**
     * 3. Anti-IDOR: Data antar user terisolasi 100%.
     */
    public function test_transaksi_hub_isolates_user_data_strictly()
    {
        // Booking & Pesanan User A
        Booking::create([
            'booking_code' => 'BK-ALPHA-001',
            'user_id' => $this->userA->id,
            'customer_name' => $this->userA->name,
            'customer_phone' => '081234567891',
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'vehicle_name' => 'Civic Turbo Alpha',
            'status' => BookingStatus::PENDING,
        ]);

        $pesananA = Pesanan::create([
            'id_user' => $this->userA->id,
            'kode_pesanan' => 'ORD-ALPHA-777',
            'tanggal_pesan' => now(),
            'status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
            'total_harga' => 6500000,
            'customer_name' => $this->userA->name,
            'whatsapp_number' => '081234567891',
        ]);
        FormPesanan::create([
            'id_pesanan' => $pesananA->id_pesanan,
            'nama_pemesan' => $this->userA->name,
            'alamat_pengiriman' => 'Jl. Sudirman 1',
            'no_hp' => '081234567891',
            'model_kendaraan' => 'Honda Civic RS Turbo',
            'warna_kendaraan' => 'Hitam Metalik',
        ]);

        // Booking & Pesanan User B
        Booking::create([
            'booking_code' => 'BK-BETA-999',
            'user_id' => $this->userB->id,
            'customer_name' => $this->userB->name,
            'customer_phone' => '089876543210',
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(4)->toDateString(),
            'booking_time' => '13:00',
            'payment_type' => 'lunas',
            'vehicle_name' => 'Pajero Sport Beta',
            'status' => BookingStatus::IN_PROGRESS,
        ]);

        // User A cek Booking
        $resBookingA = $this->actingAs($this->userA)->get(route('transaksi.index', ['type' => 'booking']));
        $resBookingA->assertStatus(200);
        $resBookingA->assertSee('BK-ALPHA-001');
        $resBookingA->assertSee('Civic Turbo Alpha');
        $resBookingA->assertDontSee('BK-BETA-999');
        $resBookingA->assertDontSee('Pajero Sport Beta');

        // User A cek Pesanan
        $resPesananA = $this->actingAs($this->userA)->get(route('transaksi.index', ['type' => 'pesanan']));
        $resPesananA->assertStatus(200);
        $resPesananA->assertSee('ORD-ALPHA-777');
        $resPesananA->assertSee('Honda Civic RS Turbo');
    }

    /**
     * 4. Filter status booking.
     */
    public function test_booking_status_tab_filtering()
    {
        Booking::create([
            'booking_code' => 'BK-FILTER-UNPAID',
            'user_id' => $this->userA->id,
            'customer_name' => $this->userA->name,
            'customer_phone' => '081234567891',
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(1)->toDateString(),
            'payment_type' => 'dp',
            'status' => BookingStatus::AWAITING_PAYMENT,
        ]);

        Booking::create([
            'booking_code' => 'BK-FILTER-DONE',
            'user_id' => $this->userA->id,
            'customer_name' => $this->userA->name,
            'customer_phone' => '081234567891',
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->subDays(5)->toDateString(),
            'payment_type' => 'lunas',
            'status' => BookingStatus::COMPLETED,
        ]);

        $resUnpaid = $this->actingAs($this->userA)->get(route('transaksi.index', [
            'type' => 'booking',
            'booking_tab' => 'unpaid',
        ]));
        $resUnpaid->assertStatus(200);
        $resUnpaid->assertSee('BK-FILTER-UNPAID');
        $resUnpaid->assertDontSee('BK-FILTER-DONE');
    }

    /**
     * 5. Halaman Pusat Tagihan: Menampilkan daftar tagihan aktif dan rekening resmi.
     */
    public function test_authenticated_user_can_view_tagihan_page_with_unpaid_items()
    {
        Booking::create([
            'booking_code' => 'BK-TAGIHAN-01',
            'user_id' => $this->userA->id,
            'customer_name' => $this->userA->name,
            'customer_phone' => '081234567891',
            'layanan_id' => $this->layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'payment_type' => 'dp',
            'vehicle_name' => 'Fortuner Alpha',
            'status' => BookingStatus::AWAITING_PAYMENT,
        ]);

        $response = $this->actingAs($this->userA)->get(route('transaksi.tagihan'));
        $response->assertStatus(200);
        $response->assertSee('Bayar Tagihan');
        $response->assertSee('BK-TAGIHAN-01');
        $response->assertSee('Fortuner Alpha');
        $response->assertSee('123-456-7890'); // No rekening BCA
    }

    /**
     * 6. Halaman Pusat Tagihan: Menampilkan status lunas jika tidak ada tagihan.
     */
    public function test_tagihan_page_shows_all_paid_when_no_unpaid_items()
    {
        $response = $this->actingAs($this->userA)->get(route('transaksi.tagihan'));
        $response->assertStatus(200);
        $response->assertSee('Semua Tagihan Lunas');
    }
}
