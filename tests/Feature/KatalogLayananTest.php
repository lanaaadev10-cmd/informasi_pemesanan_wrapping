<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Database\Seeders\LayananSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogLayananTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->customer = User::factory()->create([
            'name'              => 'Pelanggan Test',
            'email'             => 'pelanggan.test@dantiestiker.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password123'),
        ]);
        $this->customer->assignRole('user');
    }

    public function test_layanan_seeder_populates_comprehensive_services(): void
    {
        $this->seed(LayananSeeder::class);

        // Verifikasi paket Jasa Pasang Saja untuk motor dan mobil
        $jasaPasangMotor = Layanan::where('kategori', 'motor')
            ->where('tipe_paket', 'jasa-pasang')
            ->get();
        $this->assertGreaterThanOrEqual(3, $jasaPasangMotor->count());

        $jasaPasangMobil = Layanan::where('kategori', 'mobil')
            ->where('tipe_paket', 'jasa-pasang')
            ->first();
        $this->assertNotNull($jasaPasangMobil);

        // Verifikasi Full Wrap untuk motor dan mobil
        $wrapMotor = Layanan::where('kategori', 'motor')
            ->where('tipe_paket', 'wrapping')
            ->get();
        $this->assertGreaterThanOrEqual(3, $wrapMotor->count());

        $wrapMobil = Layanan::where('kategori', 'mobil')
            ->where('tipe_paket', 'wrapping')
            ->get();
        $this->assertGreaterThanOrEqual(2, $wrapMobil->count());

        // Verifikasi atribut fitur paket kendaraan
        $motorKecil = Layanan::where('nama_layanan', 'like', '%Motor Kecil%')->first();
        $this->assertNotNull($motorKecil);
        $this->assertTrue(is_array($motorKecil->fitur));
        $fiturString = json_encode($motorKecil->fitur);
        $this->assertStringContainsString('Beat', $fiturString);
        $this->assertStringContainsString('Vario', $fiturString);
    }

    public function test_booking_create_page_renders_smart_assistant_ui(): void
    {
        $this->seed(LayananSeeder::class);

        $response = $this->actingAs($this->customer)->get(route('booking.create'));

        $response->assertStatus(200);
        $response->assertSee('Asisten Pintar Pilihan Layanan');
        $response->assertSee('Tipe Kendaraan:');
        $response->assertSee('Jenis Kebutuhan:');
        $response->assertSee('Jasa Pasang');
        $response->assertSee('Full Wrap All-In');
        $response->assertSee('Tanya Teknisi via WA');
    }

    public function test_catalog_landing_page_renders_jasa_pasang_filters(): void
    {
        $this->seed(LayananSeeder::class);

        $response = $this->get(route('katalog.user'));

        $response->assertStatus(200);
        $response->assertSee('Jasa Pasang Saja');
        $response->assertSee('data-category="jasa-pasang"', false);
    }

    public function test_public_layanan_landing_page_renders_vehicle_badges(): void
    {
        $this->seed(LayananSeeder::class);

        $response = $this->get(route('layanan'));

        $response->assertStatus(200);
        $response->assertSee('🛵 Motor');
        $response->assertSee('🚗 Mobil');
        $response->assertSee('layanan-filter-section', false);
        $response->assertSee('Khusus Sepeda Motor');
        $response->assertSee('Khusus Mobil');
        $response->assertSee('Jasa Pasang Saja');
    }

    public function test_layanan_page_renders_interactive_configurator_and_comparison_modal(): void
    {
        $this->seed(LayananSeeder::class);

        $response = $this->get(route('layanan'));

        $response->assertStatus(200);
        $response->assertSee('wrapping-studio-configurator', false);
        $response->assertSee('STUDIO ESTIMASI & SIMULASI REAL-TIME');
        $response->assertSee('modal-comparison-layanan', false);
        $response->assertSee('Perbandingan: Bawa Bahan Sendiri vs Paket Lengkap Toko');
        $response->assertSee('cfgSetVehicle', false);
        $response->assertSee('cfgSetMaterial', false);
    }
}
