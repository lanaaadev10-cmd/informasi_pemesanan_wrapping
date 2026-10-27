<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Suite Pengujian Keamanan Otorisasi & Kontrol Akses
 * Memvalidasi sistem kebal terhadap:
 * 1. BOLA / IDOR (Insecure Direct Object Reference)
 * 2. Privilege Escalation (Eskalasi Hak Akses Admin)
 * 3. Proteksi Route Publik vs Terproteksi
 */
class KeamananOtorisasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $victimUser;
    protected User $attackerUser;
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

        $this->attackerUser = User::factory()->create([
            'name'              => 'Penyerang Malicious',
            'email'             => 'attacker@evil-domain.local',
            'email_verified_at' => now(),
        ]);
        $this->attackerUser->assignRole('user');

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

    public function test_idor_attacker_cannot_view_victim_booking_details(): void
    {
        $victimBooking = Booking::create([
            'booking_code'    => 'BK-VICTIM-001',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'booking_time'    => '09:00',
            'customer_name'   => 'Korban Legitim',
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic',
            'vehicle_license' => 'B 1000 VIC',
            'status'          => BookingStatus::PENDING,
        ]);

        $response = $this->actingAs($this->attackerUser)
            ->get(route('booking.show', $victimBooking->id));

        $this->assertEquals(404, $response->status());
    }

    public function test_idor_attacker_cannot_download_victim_booking_invoice(): void
    {
        $victimBooking = Booking::create([
            'booking_code'    => 'BK-VICTIM-002',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'booking_time'    => '09:00',
            'customer_name'   => 'Korban Legitim',
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic',
            'vehicle_license' => 'B 1000 VIC',
            'status'          => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->attackerUser)
            ->get(route('booking.invoice', $victimBooking->id));

        $this->assertEquals(404, $response->status());
    }

    public function test_idor_attacker_cannot_cancel_victim_booking(): void
    {
        $victimBooking = Booking::create([
            'booking_code'    => 'BK-VICTIM-003',
            'user_id'         => $this->victimUser->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->addDays(2)->toDateString(),
            'booking_time'    => '09:00',
            'customer_name'   => 'Korban Legitim',
            'customer_phone'  => '081211112222',
            'vehicle_name'    => 'Honda Civic',
            'vehicle_license' => 'B 1000 VIC',
            'status'          => BookingStatus::PENDING,
        ]);

        $response = $this->actingAs($this->attackerUser)
            ->post(route('booking.cancel', $victimBooking->id), ['alasan' => 'Dibatalkan oleh hacker']);

        $this->assertEquals(404, $response->status());
        $this->assertEquals(BookingStatus::PENDING, $victimBooking->fresh()->status);
    }

    public function test_idor_attacker_cannot_tamper_victim_cart_items(): void
    {
        $victimCart = Keranjang::create([
            'id_user' => $this->victimUser->id,
            'status'  => 'active',
        ]);
        $victimItem = DetailKeranjang::create([
            'id_keranjang' => $victimCart->id_keranjang,
            'id_paket'     => $this->layanan->id_layanan,
            'jumlah'       => 1,
            'harga_satuan' => 3500000,
            'subtotal'     => 3500000,
        ]);

        $resUpdate = $this->actingAs($this->attackerUser)
            ->patchJson(route('keranjang.update', $victimItem->id_detail), ['jumlah' => 99]);

        $resUpdate->assertStatus(403);

        $resDelete = $this->actingAs($this->attackerUser)
            ->delete(route('keranjang.hapus', $victimItem->id_detail));

        $resDelete->assertStatus(403);
        $this->assertEquals(1, $victimItem->fresh()->jumlah);
    }

    public function test_privilege_escalation_normal_user_blocked_from_filament_admin(): void
    {
        $resDashboard = $this->actingAs($this->attackerUser)->get('/admin');
        $this->assertTrue(in_array($resDashboard->status(), [403, 302]));

        $resLaporan = $this->actingAs($this->attackerUser)->get(route('admin.laporan'));
        $this->assertEquals(403, $resLaporan->status());
    }

    public function test_unauthenticated_guest_strictly_redirected_from_protected_routes(): void
    {
        $resBooking = $this->get(route('booking.create'));
        $resBooking->assertRedirect('/login');

        $resTransaksi = $this->get(route('transaksi.index'));
        $resTransaksi->assertRedirect('/login');

        $resKeranjang = $this->get(route('keranjang.index'));
        $resKeranjang->assertRedirect('/login');
    }
}
