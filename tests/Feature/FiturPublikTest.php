<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Notifikasi;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FiturPublikTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
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

        $this->layananA = Layanan::create([
            'nama_layanan' => 'Full Wrapping Satin Black',
            'kategori' => 'mobil',
            'tipe_layanan' => 'fix',
            'harga' => 4500000,
            'estimasi_waktu' => '3 Hari',
            'deskripsi' => 'Full wrapping bodi mobil dengan bahan premium satin black.',
            'status' => 'aktif',
        ]);

        Galeri::create([
            'judul' => 'Porsche 911 GT3 Satin Black',
            'kategori' => 'matte',
            'jenis' => 'Full Body',
            'foto' => 'galeri/sample.jpg',
            'tanggal_upload' => now()->toDateString(),
            'is_featured' => true,
        ]);
    }

    public function test_landing_page_beranda_renders_successfully(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Dantie');
    }

    public function test_landing_page_profil_and_tentang_kami_render(): void
    {
        $resProfil = $this->get(route('profil.perusahaan'));
        $resProfil->assertStatus(200);

        $resTentang = $this->get(route('tentang-kami'));
        $resTentang->assertStatus(200);
    }

    public function test_landing_page_layanan_and_katalog(): void
    {
        // Pengunjung tamu mengakses /layanan
        $resLayananGuest = $this->get(route('layanan'));
        $resLayananGuest->assertStatus(200);
        $resLayananGuest->assertSee('Full Wrapping Satin Black');

        // Pengguna terautentikasi dialihkan ke katalog pengguna
        $resLayananAuth = $this->actingAs($this->customer)->get(route('layanan'));
        $resLayananAuth->assertRedirect(route('katalog.user'));

        // Akses langsung katalog
        $resKatalog = $this->get(route('katalog.user'));
        $resKatalog->assertStatus(200);
        $resKatalog->assertSee('Full Wrapping Satin Black');
    }

    public function test_landing_page_galeri_and_kategori_filter(): void
    {
        $resGaleri = $this->get(route('galeri.user'));
        $resGaleri->assertStatus(200);
        $resGaleri->assertSee('Porsche 911 GT3 Satin Black');

        $resKategori = $this->get(route('galeri.kategori', ['kategori' => 'matte']));
        $resKategori->assertStatus(200);
        $resKategori->assertSee('Porsche 911 GT3 Satin Black');
    }

    public function test_landing_page_kebijakan_privasi_and_testimoni(): void
    {
        $resPrivasi = $this->get(route('kebijakan-privasi'));
        $resPrivasi->assertStatus(200);

        $resTestimoni = $this->get(route('testimoni.index'));
        $resTestimoni->assertStatus(200);
    }

    public function test_kalkulator_wrapping_redirects_to_booking(): void
    {
        $response = $this->get('/kalkulator-wrapping');
        $response->assertRedirect('/booking/buat');
    }

    public function test_metrics_endpoint_ip_security(): void
    {
        // Akses dari localhost diizinkan
        $resLocal = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        $resLocal->assertStatus(200);

        // Akses dari IP eksternal ditolak dengan 403 Forbidden
        $resForeign = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '203.0.113.195']);
        $resForeign->assertStatus(403);
    }

    public function test_api_public_layanan_and_galeri(): void
    {
        $resApiLayanan = $this->getJson('/api/layanan');
        $resApiLayanan->assertStatus(200);
        $resApiLayanan->assertJsonStructure([
            'status',
            'data',
        ]);

        $resApiGaleri = $this->getJson('/api/galeri');
        $resApiGaleri->assertStatus(200);
        $resApiGaleri->assertJsonStructure(['data']);

        $resApiCategories = $this->getJson('/api/galeri/kategori');
        $resApiCategories->assertStatus(200);
        $resApiCategories->assertJsonStructure(['data']);
    }

    public function test_api_booking_public_quotas(): void
    {
        $resQuotaToday = $this->getJson('/api/booking/quota-today');
        $resQuotaToday->assertStatus(200);
        $resQuotaToday->assertJsonStructure([
            'date',
            'quota' => [
                'available',
                'is_full',
            ],
        ]);

        $tomorrow = now()->addDay()->toDateString();
        $resQuotaDate = $this->getJson("/api/booking/quota/{$tomorrow}");
        $resQuotaDate->assertStatus(200);
        $resQuotaDate->assertJsonStructure([
            'date',
            'quota' => [
                'available',
                'is_full',
            ],
        ]);
    }

    public function test_api_protected_sanctum_cart_and_notifications(): void
    {
        // Tamu tanpa token otentikasi mengakses /api/keranjang -> 401 Unauthorized
        $resUnauth = $this->getJson('/api/keranjang');
        $resUnauth->assertStatus(401);

        // Pelanggan terautentikasi melalui Sanctum
        Sanctum::actingAs($this->customer);

        $resCart = $this->getJson('/api/keranjang');
        $resCart->assertStatus(200);

        $resCartCount = $this->getJson('/api/keranjang/count');
        $resCartCount->assertStatus(200);

        // Buat data notifikasi reservasi
        $booking = Reservasi::create([
            'booking_code' => 'BKG-NOTIF-001',
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'vehicle_name' => 'Toyota Avanza',
            'status' => BookingStatus::PENDING,
        ]);

        Notifikasi::create([
            'id_user' => $this->customer->id,
            'booking_id' => $booking->id,
            'judul' => 'Booking Diajukan',
            'pesan' => 'Booking Anda sedang diproses.',
            'tipe' => 'in_app',
        ]);

        $resNotif = $this->getJson('/api/notifikasi');
        $resNotif->assertStatus(200);
        $this->assertEquals($booking->id, $resNotif->json('data.0.booking_id'));

        $resNotifCount = $this->getJson('/api/notifikasi/unread-count');
        $resNotifCount->assertStatus(200);
    }
}
