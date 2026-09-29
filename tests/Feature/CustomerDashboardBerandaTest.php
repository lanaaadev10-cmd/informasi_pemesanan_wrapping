<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardBerandaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard customer
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_customer_can_render_dashboard_with_all_12_features(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);

        // Verifikasi keberadaan Aksi Cepat dan trigger Semua Fitur
        $response->assertSee('Aksi Cepat');
        $response->assertSee('Semua Fitur (12)');

        // Verifikasi ketiga grup fitur
        $response->assertSee('Layanan &amp; Reservasi', false);
        $response->assertSee('Transaksi &amp; Pembayaran', false);
        $response->assertSee('Workshop &amp; Bantuan', false);

        // Verifikasi ke-12 fitur aksi cepat
        $response->assertSee(route('booking.create'));
        $response->assertSee(route('katalog.user'));
        $response->assertSee(route('testimoni.index'));
        $response->assertSee(route('keranjang.index'));
        $response->assertSee(route('transaksi.index'));
        $response->assertSee(route('booking.index'));
        $response->assertSee(route('pesanan.index'));
        $response->assertSee(route('galeri.user'));
        $response->assertSee(route('profil.perusahaan'));
        $response->assertSee(route('profile.edit'));

        // Verifikasi kalender kotak
        $response->assertSee('booking-calendar');
        $response->assertSee('Ketersediaan Slot');
        $response->assertSee('bookingCalWidget()');
    }

    public function test_booking_quota_month_api_returns_valid_json(): void
    {
        $year = now()->year;
        $month = now()->month;

        $response = $this->getJson("/api/booking/quota-month/{$year}/{$month}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'year',
            'month',
            'quota',
        ]);
    }

    public function test_booking_day_detail_api_returns_valid_json(): void
    {
        $date = now()->format('Y-m-d');

        $response = $this->getJson("/api/booking/day-detail/{$date}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'date',
            'quota' => ['available', 'total_used', 'is_full', 'max'],
            'slots',
        ]);
    }
}
