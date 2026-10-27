<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReservasiTest extends TestCase
{
    use RefreshDatabase;

    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        Storage::fake('public');
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Premium',
            'deskripsi' => 'Layanan pembungkusan bodi mobil secara menyeluruh.',
            'harga' => 5000000,
            'tipe_layanan' => 'fix',
            'estimasi_waktu' => '2 Hari',
            'status' => 'aktif',
        ]);
    }

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

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString(
            'Invoice-BKG-INV-TEST-001.pdf',
            $response->headers->get('Content-Disposition')
        );
    }

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
