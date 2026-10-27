<?php

namespace Tests\Feature;

use App\Helpers\WhatsAppHelper;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoTierCascadingSelectorTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // Seed data uji representatif untuk ke-4 pilar & 2 jenis kendaraan
        Layanan::create([
            'nama_layanan'   => 'Variasi Mobil Standar',
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'wrapping',
            'jenis_layanan'  => 'paket',
            'harga'          => 3500000,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Kaca Film Solargard Mobil',
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'window-film',
            'jenis_layanan'  => 'paket',
            'harga'          => 450000,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Jasa Pasang Decal Motor Sport (Bahan Sendiri)',
            'tipe_layanan'   => 'Motor',
            'kategori'       => 'motor',
            'tipe_paket'     => 'jasa-pasang',
            'jenis_layanan'  => 'jasa_pasang',
            'harga'          => 200000,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Jasa Pasang Panel Kap Mobil (Bahan Sendiri)',
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'jasa-pasang',
            'jenis_layanan'  => 'jasa_pasang',
            'harga'          => 250000,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Full Wrap Motor Maxi Oracal',
            'tipe_layanan'   => 'Motor',
            'kategori'       => 'motor',
            'tipe_paket'     => 'wrapping',
            'jenis_layanan'  => 'paket',
            'harga'          => 750000,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Custom Decal Mobil Racing',
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'custom',
            'jenis_layanan'  => 'paket',
            'harga'          => 0,
            'is_active'      => true,
        ]);

        Layanan::create([
            'nama_layanan'   => 'Striping Motor Beat',
            'tipe_layanan'   => 'Motor',
            'kategori'       => 'motor',
            'tipe_paket'     => 'striping',
            'jenis_layanan'  => 'paket',
            'harga'          => 150000,
            'is_active'      => true,
        ]);
    }

    /**
     * 1. Verifikasi accessor Model Layanan mengklasifikasi 4 Pilar dan WhatsApp dengan tepat
     */
    public function test_layanan_model_categorizes_correctly_into_four_pillars(): void
    {
        $wrapMobil = Layanan::where('nama_layanan', 'Variasi Mobil Standar')->first();
        $this->assertEquals('full-wrap', $wrapMobil->pillar);
        $this->assertEquals('Full Wrap', $wrapMobil->pillar_label);
        $this->assertFalse($wrapMobil->isWhatsappOnly());

        $aksesorisMobil = Layanan::where('nama_layanan', 'Kaca Film Solargard Mobil')->first();
        $this->assertEquals('aksesoris', $aksesorisMobil->pillar);
        $this->assertEquals('Aksesoris', $aksesorisMobil->pillar_label);
        $this->assertFalse($aksesorisMobil->isWhatsappOnly());

        $jasaMotor = Layanan::where('nama_layanan', 'Jasa Pasang Decal Motor Sport (Bahan Sendiri)')->first();
        $this->assertEquals('jasa-pasang', $jasaMotor->pillar);
        $this->assertEquals('Jasa Pasang', $jasaMotor->pillar_label);
        $this->assertTrue($jasaMotor->isWhatsappOnly());

        $jasaMobil = Layanan::where('nama_layanan', 'Jasa Pasang Panel Kap Mobil (Bahan Sendiri)')->first();
        $this->assertEquals('jasa-pasang', $jasaMobil->pillar);
        $this->assertTrue($jasaMobil->isWhatsappOnly());

        $customMobil = Layanan::where('nama_layanan', 'Custom Decal Mobil Racing')->first();
        $this->assertEquals('custom', $customMobil->pillar);
        $this->assertEquals('Custom', $customMobil->pillar_label);
        $this->assertTrue($customMobil->isWhatsappOnly());

        $customMotor = Layanan::where('nama_layanan', 'Striping Motor Beat')->first();
        $this->assertEquals('custom', $customMotor->pillar);
        $this->assertTrue($customMotor->isWhatsappOnly());
    }

    /**
     * 2. Verifikasi User Dashboard merender Tier 1 (Selector Jenis Kendaraan)
     */
    public function test_user_dashboard_renders_tier_1_vehicle_segmented_control(): void
    {
        $response = $this->actingAs($this->customer)->get(route('dashboard'));
        $response->assertStatus(200);

        // Verifikasi container selector
        $response->assertSee('cascading-selector-container');
        $response->assertSee('Tier 1 — Jenis Kendaraan:');

        // Verifikasi ketiga tombol Tier 1
        $response->assertSee('id="tier1-btn-all"', false);
        $response->assertSee('id="tier1-btn-mobil"', false);
        $response->assertSee('id="tier1-btn-motor"', false);

        // Verifikasi data attributes
        $response->assertSee('data-vehicle="all"', false);
        $response->assertSee('data-vehicle="mobil"', false);
        $response->assertSee('data-vehicle="motor"', false);
    }

    /**
     * 3. Verifikasi User Dashboard merender Tier 2 (Selector Empat Pilar Kebutuhan)
     */
    public function test_user_dashboard_renders_tier_2_four_pillars_selector(): void
    {
        $response = $this->actingAs($this->customer)->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertSee('Tier 2 — Pilar Kebutuhan:');

        // Verifikasi ke-5 tombol (Semua + 4 Pilar)
        $response->assertSee('id="tier2-btn-all"', false);
        $response->assertSee('id="tier2-btn-jasa-pasang"', false);
        $response->assertSee('id="tier2-btn-full-wrap"', false);
        $response->assertSee('id="tier2-btn-custom"', false);
        $response->assertSee('id="tier2-btn-aksesoris"', false);

        // Verifikasi data attributes pilar
        $response->assertSee('data-pillar="all"', false);
        $response->assertSee('data-pillar="jasa-pasang"', false);
        $response->assertSee('data-pillar="full-wrap"', false);
        $response->assertSee('data-pillar="custom"', false);
        $response->assertSee('data-pillar="aksesoris"', false);
    }

    /**
     * 4. Verifikasi Kartu Produk memiliki Atribut Filter dan Tombol Sesuai Alur Bisnis
     */
    public function test_product_cards_have_filter_attributes_and_business_aligned_buttons(): void
    {
        $response = $this->actingAs($this->customer)->get(route('dashboard'));
        $response->assertStatus(200);

        // Kartu produk memiliki class dan atribut data-vehicle dan data-pillar
        $response->assertSee('dash-pick-card');
        $response->assertSee('data-vehicle="mobil"', false);
        $response->assertSee('data-vehicle="motor"', false);
        $response->assertSee('data-pillar="full-wrap"', false);
        $response->assertSee('data-pillar="jasa-pasang"', false);
        $response->assertSee('data-pillar="custom"', false);
        $response->assertSee('data-pillar="aksesoris"', false);

        // Alur WhatsApp untuk Jasa Pasang dan Custom
        $response->assertSee(e(WhatsAppHelper::getJasaUrl()), false);
        $response->assertSee(e(WhatsAppHelper::getCustomUrl()), false);
        $response->assertSee('Konsultasi WA');
        $response->assertSee('Desain via WA');

        // Alur Keranjang + Booking untuk Full Wrap & Aksesoris
        $response->assertSee(route('booking.create'));
        $response->assertSee(route('keranjang.tambah'));
    }

    /**
     * 5. Verifikasi Empty State dan Tombol Reset Filter Tersedia di DOM
     */
    public function test_empty_state_and_reset_button_exist_in_dom(): void
    {
        $response = $this->actingAs($this->customer)->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertSee('id="dash-filter-empty"', false);
        $response->assertSee('Tidak Ditemukan Layanan yang Cocok');
        $response->assertSee('resetCascadingFilters()', false);
        $response->assertSee('Reset ke Semua Layanan');
    }

    /**
     * 6. Verifikasi Grid Produk Responsif dengan 2 Kolom di HP dan Jarak Samping 5px
     */
    public function test_mobile_grid_two_columns_and_five_px_gap_layout(): void
    {
        $response = $this->actingAs($this->customer)->get(route('dashboard'));
        $response->assertStatus(200);

        // Verifikasi kelas CSS 2 kolom mobile dan jarak 5px
        $response->assertSee('grid-cols-2');
        $response->assertSee('gap-x-[5px]');
        $response->assertSee('px-[5px]');
    }
}
