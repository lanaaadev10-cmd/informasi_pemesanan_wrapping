<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Layanan;
use App\Models\PembayaranReservasi;
use App\Models\Pesanan;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanDanPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $admin;
    protected Layanan $layananA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->customer = User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@example.com',
            'email_verified_at' => now(),
        ]);
        $this->customer->assignRole('user');

        $this->admin = User::factory()->create([
            'name' => 'Admin Dantie',
            'email' => 'admin@dantie.com',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        $this->layananA = Layanan::create([
            'nama_layanan' => 'Full Wrapping Satin Black',
            'kategori' => 'mobil',
            'tipe_layanan' => 'fix',
            'harga' => 4500000,
            'estimasi_waktu' => '3 Hari',
            'deskripsi' => 'Full wrapping bodi mobil dengan bahan premium satin black.',
            'status' => 'aktif',
        ]);
    }

    public function test_admin_laporan_strictly_forbidden_for_normal_customer(): void
    {
        $resCustomer = $this->actingAs($this->customer)->get(route('admin.laporan'));
        $this->assertTrue(in_array($resCustomer->status(), [403, 302]));

        $resAdmin = $this->actingAs($this->admin)->get(route('admin.laporan', ['type' => 'hari']));
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Laporan Penjualan Transaksi');
    }

    public function test_admin_laporan_unifies_both_pesanan_and_booking_payments(): void
    {
        Pesanan::create([
            'id_user' => $this->customer->id,
            'kode_pesanan' => 'PSN-UNIFY-001',
            'tanggal_pesan' => now(),
            'status' => Pesanan::STATUS_SELESAI,
            'total_harga' => 750000,
        ]);

        $booking = Reservasi::create([
            'booking_code' => 'BKG-UNIFY-001',
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'vehicle_name' => 'Toyota Avanza',
            'status' => BookingStatus::CONFIRMED,
        ]);

        PembayaranReservasi::create([
            'booking_id' => $booking->id,
            'payment_method' => 'BCA',
            'amount' => 250000,
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $resAdmin = $this->actingAs($this->admin)->get(route('admin.laporan', ['type' => 'hari']));
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('PSN-UNIFY-001');
        $resAdmin->assertSee('BKG-UNIFY-001');
        $resAdmin->assertSee('1.000.000');
    }

    public function test_filament_admin_panel_strictly_forbidden_for_normal_customer(): void
    {
        $resCustomer = $this->actingAs($this->customer)->get('/admin');
        $this->assertTrue(in_array($resCustomer->status(), [403, 302]));

        $resAdmin = $this->actingAs($this->admin)->get('/admin');
        $resAdmin->assertStatus(200);
    }

    public function test_filament_admin_can_render_bookings_table_with_various_statuses(): void
    {
        Reservasi::create([
            'booking_code' => 'BK-TEST-ADMIN-01',
            'user_id' => $this->customer->id,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->addDays(2),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'status' => BookingStatus::PENDING,
            'vehicle_name' => 'Honda Civic',
        ]);

        Reservasi::create([
            'booking_code' => 'BK-TEST-ADMIN-02',
            'user_id' => $this->customer->id,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->addDays(3),
            'booking_time' => '13:00',
            'payment_type' => 'lunas',
            'status' => BookingStatus::APPROVED,
            'vehicle_name' => 'Toyota Fortuner',
        ]);

        $resAdmin = $this->actingAs($this->admin)->get('/admin/booking');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('BK-TEST-ADMIN-01');
        $resAdmin->assertSee('BK-TEST-ADMIN-02');
    }
}
