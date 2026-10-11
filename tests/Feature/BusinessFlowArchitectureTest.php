<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\Layanan;
use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessFlowArchitectureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected Layanan $paketLayanan;
    protected Layanan $jasaPasangLayanan;
    protected Layanan $customLayanan;
    protected VehicleBrand $brand;
    protected VehicleModel $model;
    protected Addon $addon1;
    protected Addon $addon2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->user->assignRole('user');

        $this->admin = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        $this->paketLayanan = Layanan::create([
            'nama_layanan'    => 'Full Wrap Dantie Glossy',
            'jenis_layanan'   => 'paket',
            'tipe_layanan'    => 'fix',
            'kategori'        => 'mobil',
            'tipe_paket'      => 'wrapping',
            'harga'           => 3000000,
            'estimasi_waktu'  => '2-3 Hari',
            'is_active'       => true,
        ]);

        $this->jasaPasangLayanan = Layanan::create([
            'nama_layanan'    => 'Jasa Pasang Decal Mobil (Bahan Sendiri)',
            'jenis_layanan'   => 'jasa_pasang',
            'tipe_layanan'    => 'fix',
            'kategori'        => 'mobil',
            'tipe_paket'      => 'jasa-pasang',
            'harga'           => 1200000,
            'estimasi_waktu'  => '1-2 Hari',
            'is_active'       => true,
        ]);

        $this->customLayanan = Layanan::create([
            'nama_layanan'    => 'Custom Project Full Livery Racing',
            'jenis_layanan'   => 'custom',
            'tipe_layanan'    => 'custom',
            'kategori'        => 'mobil',
            'tipe_paket'      => 'custom',
            'harga'           => 100000, // base placeholder
            'estimasi_waktu'  => '3-5 Hari',
            'is_active'       => true,
        ]);

        $this->brand = VehicleBrand::create([
            'name'         => 'Honda',
            'vehicle_type' => 'mobil',
            'is_active'    => true,
        ]);

        $this->model = VehicleModel::create([
            'brand_id'     => $this->brand->id,
            'name'         => 'Civic Turbo',
            'vehicle_size' => 'medium',
            'is_active'    => true,
        ]);

        $this->addon1 = Addon::create([
            'name'        => 'Pembersihan Lem Stiker Lama',
            'category'    => 'mobil',
            'price'       => 250000,
            'is_active'   => true,
        ]);

        $this->addon2 = Addon::create([
            'name'        => 'Coating Vinyl Protection',
            'category'    => 'all',
            'price'       => 500000,
            'is_active'   => true,
        ]);
    }

    public function test_api_public_endpoints_for_vehicles_and_addons(): void
    {
        // 1. Get brands
        $response = $this->getJson('/api/vehicles/brands');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['name' => 'Honda']);

        // 2. Get models by brand
        $response = $this->getJson('/api/vehicles/models/' . $this->brand->id);
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['name' => 'Civic Turbo', 'vehicle_size' => 'medium']);

        // 3. Get addons
        $response = $this->getJson('/api/addons');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['name' => 'Pembersihan Lem Stiker Lama'])
            ->assertJsonFragment(['name' => 'Coating Vinyl Protection']);
    }

    public function test_booking_creation_with_vehicle_model_size_and_addons(): void
    {
        $bookingDate = Carbon::now()->addDays(2)->toDateString();

        $payload = [
            'layanan_id'      => $this->paketLayanan->id_layanan,
            'booking_date'    => $bookingDate,
            'booking_time'    => '08:30',
            'payment_type'    => 'dp',
            'payment_method'  => 'Transfer BCA',
            'customer_name'   => 'Rian Firmansyah',
            'customer_phone'  => '081234567890',
            'customer_email'  => 'rian@example.com',
            'brand_id'        => $this->brand->id,
            'model_id'        => $this->model->id,
            'vehicle_color'   => 'Putih Pearl',
            'vehicle_license' => 'B 1234 RF',
            'addons'          => [$this->addon1->id, $this->addon2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('booking.store'), $payload);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $booking = Booking::where('customer_phone', '081234567890')->first();
        $this->assertNotNull($booking);

        // Verifikasi relasi & field kendaraan
        $this->assertEquals($this->brand->id, $booking->brand_id);
        $this->assertEquals($this->model->id, $booking->model_id);
        $this->assertEquals('medium', $booking->vehicle_size);
        $this->assertEquals('Honda Civic Turbo', $booking->vehicle_name);
        $this->assertEquals('online', $booking->source);

        // Verifikasi snapshot keuangan
        // Base: 3.000.000, Addons: 250.000 + 500.000 = 750.000. Grand total: 3.750.000
        // DP: 1.500.000 + 375.000 = 1.875.000, Sisa: 1.875.000
        $this->assertEquals('Full Wrap Dantie Glossy', $booking->service_name_snapshot);
        $this->assertEquals(3000000, (float) $booking->base_price_snapshot);
        $this->assertEquals(750000, (float) $booking->addons_total_snapshot);
        $this->assertEquals(3750000, (float) $booking->grand_total_snapshot);
        $this->assertEquals(1875000, (float) $booking->dp_amount_snapshot);
        $this->assertEquals(1875000, (float) $booking->remaining_balance_snapshot);

        // Verifikasi booking addons tersimpan
        $this->assertCount(2, $booking->bookingAddons);
        $this->assertDatabaseHas('booking_addons', [
            'booking_id'     => $booking->id,
            'addon_id'       => $this->addon1->id,
            'price_snapshot' => 250000,
        ]);
        $this->assertDatabaseHas('booking_addons', [
            'booking_id'     => $booking->id,
            'addon_id'       => $this->addon2->id,
            'price_snapshot' => 500000,
        ]);
    }

    public function test_jasa_pasang_material_declaration_and_disclaimer(): void
    {
        Storage::fake('public');
        $bookingDate = Carbon::now()->addDays(3)->toDateString();
        $file = UploadedFile::fake()->image('bahan_oracal.jpg');

        $payload = [
            'layanan_id'                 => $this->jasaPasangLayanan->id_layanan,
            'booking_date'               => $bookingDate,
            'booking_time'               => '10:00',
            'payment_type'               => 'dp',
            'customer_name'              => 'Doni Pratama',
            'customer_phone'             => '082199887766',
            'material_brand'             => 'Oracal 651 Glossy Black',
            'material_size'              => '1 Roll (18 Meter)',
            'material_condition'         => 'baru',
            'material_disclaimer_agreed' => '1',
            'material_photo'             => $file,
        ];

        $response = $this->actingAs($this->user)->post(route('booking.store'), $payload);
        $response->assertSessionHasNoErrors();

        $booking = Booking::where('customer_phone', '082199887766')->first();
        $this->assertNotNull($booking);

        $this->assertEquals('Oracal 651 Glossy Black', $booking->material_brand);
        $this->assertEquals('1 Roll (18 Meter)', $booking->material_size);
        $this->assertEquals('baru', $booking->material_condition);
        $this->assertTrue((bool) $booking->material_disclaimer_agreed);
        $this->assertEquals('pending', $booking->material_status);
        $this->assertNotNull($booking->material_photo);
        Storage::disk('public')->assertExists($booking->material_photo);
    }

    public function test_custom_service_quotation_workflow(): void
    {
        Storage::fake('public');
        $bookingDate = Carbon::now()->addDays(4)->toDateString();
        $designFile = UploadedFile::fake()->image('sketsa_livery.png');

        $payload = [
            'layanan_id'             => $this->customLayanan->id_layanan,
            'booking_date'           => $bookingDate,
            'booking_time'           => '13:30',
            'payment_type'           => 'dp',
            'customer_name'          => 'Farhan Grafika',
            'customer_phone'         => '085711223344',
            'custom_request_notes'   => 'Konsep livery anime full body samping dan kap mesin warna neon.',
            'custom_reference_photo' => $designFile,
        ];

        $response = $this->actingAs($this->user)->post(route('booking.store'), $payload);
        $response->assertSessionHasNoErrors();

        $booking = Booking::where('customer_phone', '085711223344')->first();
        $this->assertNotNull($booking);

        $this->assertEquals('pending', $booking->quotation_status);
        $this->assertEquals('Konsep livery anime full body samping dan kap mesin warna neon.', $booking->custom_request_notes);
        $this->assertNotNull($booking->custom_reference_photo);
        Storage::disk('public')->assertExists($booking->custom_reference_photo);
    }

    public function test_admin_can_create_custom_booking_post_whatsapp_deal(): void
    {
        $bookingService = app(\App\Services\BookingService::class);
        $bookingDate = Carbon::now()->addDays(5)->toDateString();

        $data = [
            'tipe_pemesanan'      => 'custom',
            'custom_project_name' => 'Custom Livery Itasha Anime Full Body',
            'quotation_amount'    => 7500000,
            'quotation_notes'     => 'Deal WA: Bahan TeckWrap Neon, estimasi 4 hari pengerjaan',
            'user_id'             => $this->user->id,
            'customer_name'       => $this->user->name,
            'customer_phone'      => $this->user->nomor_telepon ?? '081234567890',
            'customer_email'      => $this->user->email,
            'booking_date'        => $bookingDate,
            'booking_time'        => '09:00',
            'vehicle_type'        => 'mobil',
            'license_plate'       => 'B 9999 DEAL',
            'catatan'             => 'Mobil sudah dicuci bersih sebelum serah terima',
            'status'              => 'confirmed',
            'payment_status'      => 'pending',
        ];

        $booking = $bookingService->createManualBooking($data);

        $this->assertNotNull($booking);
        $this->assertNull($booking->layanan_id);
        $this->assertEquals('custom', $booking->jenis_layanan);
        $this->assertEquals('Custom Livery Itasha Anime Full Body', $booking->service_name_snapshot);
        $this->assertEquals(7500000, (float) $booking->total_price);
        $this->assertEquals(3750000, (float) $booking->dp_amount);
        $this->assertEquals(3750000, (float) $booking->remaining_amount);
        $this->assertEquals('sent', $booking->quotation_status);
        $this->assertEquals($this->user->id, $booking->user_id);

        // Verify customer can view their custom booking detail & invoice
        $responseShow = $this->actingAs($this->user)->get(route('booking.show', $booking));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Custom Livery Itasha Anime Full Body');

        $responseInvoice = $this->actingAs($this->user)->get(route('booking.invoice', $booking));
        $responseInvoice->assertStatus(200);
        $responseInvoice->assertHeader('content-type', 'application/pdf');
    }
}
