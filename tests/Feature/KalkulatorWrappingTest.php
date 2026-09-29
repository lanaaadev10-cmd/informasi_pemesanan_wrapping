<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalkulatorWrappingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->layanan = Layanan::create([
            'nama_layanan' => 'Full Wrapping Body Mobil',
            'deskripsi' => 'Paket full wrapping seluruh panel luar kendaraan.',
            'harga' => 4500000,
            'tipe_layanan' => 'fix',
            'estimasi_waktu' => '3 Hari',
        ]);
    }

    /**
     * 1. Guest publik dapat mengakses halaman Kalkulator Wrapping tanpa login.
     */
    public function test_guest_can_access_kalkulator_page()
    {
        $response = $this->get(route('kalkulator.index'));

        $response->assertStatus(200);
        $response->assertSee('Interactive Wrap Studio');
        $response->assertSee('Kalkulator Estimasi Biaya');
        $response->assertSee('Pilih Ukuran Kendaraan');
        $response->assertSee('Pilih Cakupan Pengerjaan');
        $response->assertSee('Ringkasan Estimasi');
    }

    /**
     * 2. User terautentikasi dapat membuka kalkulator dan melihat tata letak terpadu.
     */
    public function test_authenticated_user_can_access_kalkulator()
    {
        $response = $this->actingAs($this->user)->get(route('kalkulator.index'));

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Estimasi');
    }

    /**
     * 3. API Hitung: City Car (Small) + Full Wrap + Glossy.
     */
    public function test_kalkulator_calculates_small_car_glossy_accurately()
    {
        $response = $this->postJson(route('kalkulator.hitung'), [
            'kategori_kendaraan' => 'small',
            'cakupan_layanan'    => 'full_wrap',
            'material_finish'    => 'glossy',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'category_key'            => 'small',
                'scope_key'               => 'full_wrap',
                'finish_key'              => 'glossy',
                'panjang_bahan_meter'     => 13,
                'panjang_bahan_formatted' => '13 Meter Roll',
                'estimasi_harga'          => 4500000,
                'estimasi_durasi'         => '2 - 3 Hari',
                'garansi'                 => '2 Tahun Garansi Resmi',
            ],
        ]);
    }

    /**
     * 4. Validasi input ditolak jika parameter di luar whitelist.
     */
    public function test_kalkulator_rejects_invalid_inputs()
    {
        $response = $this->postJson(route('kalkulator.hitung'), [
            'kategori_kendaraan' => 'truk_gandeng',
            'cakupan_layanan'    => 'bemper_saja',
            'material_finish'    => 'kertas_kado',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kategori_kendaraan', 'cakupan_layanan', 'material_finish']);
    }

    /**
     * 5. Link Direct Booking terisi query params dengan tepat.
     */
    public function test_kalkulator_generates_direct_booking_cta_url()
    {
        $response = $this->postJson(route('kalkulator.hitung'), [
            'kategori_kendaraan' => 'medium',
            'cakupan_layanan'    => 'full_wrap',
            'material_finish'    => 'satin_metallic',
        ]);

        $response->assertStatus(200);
        $bookingUrl = $response->json('data.booking_url');

        $this->assertNotEmpty($bookingUrl);
        $this->assertStringContainsString('/booking/buat', $bookingUrl);
        $this->assertStringContainsString('layanan_id=', $bookingUrl);
        $this->assertStringContainsString('vehicle_name=', $bookingUrl);
        $this->assertStringContainsString('notes=', $bookingUrl);
    }
}
