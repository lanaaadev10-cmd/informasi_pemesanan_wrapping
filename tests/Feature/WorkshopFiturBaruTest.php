<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingProgressPhoto;
use App\Models\EWarranty;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkshopFiturBaruTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected User $admin;
    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->customer = User::factory()->create([
            'name'              => 'Budi Customer',
            'email'             => 'budi@wrapping.test',
            'email_verified_at' => now(),
            'phone'             => '081299887766',
        ]);
        $this->customer->assignRole('user');

        $this->otherCustomer = User::factory()->create([
            'name'              => 'Siti Customer',
            'email'             => 'siti@wrapping.test',
            'email_verified_at' => now(),
            'phone'             => '085711223344',
        ]);
        $this->otherCustomer->assignRole('user');

        $this->admin = User::factory()->create([
            'name'              => 'Admin Workshop',
            'email'             => 'admin@dantie.test',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        $this->layanan = Layanan::create([
            'nama_layanan'   => 'Super Glossy Wrap Red',
            'jenis_layanan'  => 'paket',
            'tipe_layanan'   => 'fix',
            'kategori'       => 'mobil',
            'harga'          => 4500000,
            'estimasi_waktu' => '3 Hari',
            'is_active'      => true,
        ]);
    }

    public function test_customer_can_download_e_warranty_pdf_when_completed(): void
    {
        $booking = Booking::create([
            'booking_code'    => 'BK-WRN-001',
            'user_id'         => $this->customer->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->toDateString(),
            'customer_name'   => 'Budi Customer',
            'customer_phone'  => '081299887766',
            'vehicle_name'    => 'Civic Type R',
            'vehicle_license' => 'B 1234 BDI',
            'status'          => BookingStatus::COMPLETED,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('booking.garansi', $booking->id));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition') ?? '', '.pdf'));

        // Pastikan record e-warranty dibuat
        $this->assertDatabaseHas('e_warranties', [
            'booking_id' => $booking->id,
            'status'     => 'active',
        ]);
    }

    public function test_customer_cannot_download_other_customer_e_warranty(): void
    {
        $booking = Booking::create([
            'booking_code'    => 'BK-WRN-SEC',
            'user_id'         => $this->customer->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->toDateString(),
            'customer_name'   => 'Budi Customer',
            'customer_phone'  => '081299887766',
            'vehicle_name'    => 'Civic Type R',
            'status'          => BookingStatus::COMPLETED,
        ]);

        // Pelanggan lain mencoba mengunduh garansi Budi -> Harus ditolak (404/403)
        $response = $this->actingAs($this->otherCustomer)
            ->get(route('booking.garansi', $booking->id));

        $this->assertEquals(404, $response->status());
    }

    public function test_customer_cannot_download_e_warranty_if_not_completed(): void
    {
        $booking = Booking::create([
            'booking_code'    => 'BK-WRN-PENDING',
            'user_id'         => $this->customer->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->toDateString(),
            'customer_name'   => 'Budi Customer',
            'vehicle_name'    => 'Civic Type R',
            'status'          => BookingStatus::IN_PROGRESS,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('booking.garansi', $booking->id));

        // Dialihkan kembali dengan notifikasi error bahwa belum selesai
        $response->assertRedirect();
        $response->assertSessionHas('toast_error');
    }

    public function test_booking_progress_photos_render_on_customer_page(): void
    {
        Storage::fake('public');

        $booking = Booking::create([
            'booking_code'    => 'BK-PHOTO-TEST',
            'user_id'         => $this->customer->id,
            'layanan_id'      => $this->layanan->id_layanan,
            'booking_date'    => now()->toDateString(),
            'customer_name'   => 'Budi Customer',
            'vehicle_name'    => 'Mazda 3 Hatchback',
            'status'          => BookingStatus::IN_PROGRESS,
        ]);

        $file = UploadedFile::fake()->image('bodi_samping.jpg');
        $path = $file->store('progress_photos', 'public');

        BookingProgressPhoto::create([
            'booking_id'     => $booking->id,
            'stage'          => 'Pemasangan Vinyl Utama',
            'photo_path'     => $path,
            'caption'        => 'Pemasangan vinyl kap mesin dan pintu kiri selesai mulus.',
            'uploaded_by_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('booking.show', $booking->id));

        $response->assertStatus(200);
        $response->assertSee('Foto Progres Pengerjaan Mobil Anda');
        $response->assertSee('Pemasangan Vinyl Utama');
        $response->assertSee('Pemasangan vinyl kap mesin dan pintu kiri selesai mulus.');
    }

    public function test_workshop_kanban_page_accessible_by_admin(): void
    {
        $resCustomer = $this->actingAs($this->customer)->get('/admin/workshop-kanban');
        $this->assertTrue(in_array($resCustomer->status(), [403, 302]));

        $resAdmin = $this->actingAs($this->admin)->get('/admin/workshop-kanban');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Papan Visual Pengerjaan Mobil');
        $resAdmin->assertSee('Sedang Dikerjakan (WIP)');
    }
}
