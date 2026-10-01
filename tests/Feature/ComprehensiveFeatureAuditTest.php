<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\DetailKeranjang;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Galeri;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ComprehensiveFeatureAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected User $admin;
    protected Layanan $layananA;
    protected Layanan $layananB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        // 1. Create standard customer
        $this->customer = User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@example.com',
            'email_verified_at' => now(),
        ]);
        $this->customer->assignRole('user');

        // 2. Create another customer for IDOR/isolation testing
        $this->otherCustomer = User::factory()->create([
            'name' => 'Siti Customer',
            'email' => 'siti@example.com',
            'email_verified_at' => now(),
        ]);
        $this->otherCustomer->assignRole('user');

        // 3. Create administrator
        $this->admin = User::factory()->create([
            'name' => 'Admin Dantie',
            'email' => 'admin@dantie.com',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

        // 4. Seed test services
        $this->layananA = Layanan::create([
            'nama_layanan' => 'Full Wrapping Satin Black',
            'kategori' => 'mobil',
            'tipe_layanan' => 'fix',
            'harga' => 4500000,
            'estimasi_waktu' => '3 Hari',
            'deskripsi' => 'Full wrapping bodi mobil dengan bahan premium satin black.',
            'status' => 'aktif',
        ]);

        $this->layananB = Layanan::create([
            'nama_layanan' => 'Kaca Film Ceramic 80%',
            'kategori' => 'mobil',
            'tipe_layanan' => 'fix',
            'harga' => 1500000,
            'estimasi_waktu' => '1 Hari',
            'deskripsi' => 'Kaca film tolak panas tinggi 99% UV rejection.',
            'status' => 'aktif',
        ]);

        // 5. Seed test gallery
        Galeri::create([
            'judul' => 'Porsche 911 GT3 Satin Black',
            'kategori' => 'matte',
            'jenis' => 'Full Body',
            'foto' => 'galeri/sample.jpg',
            'tanggal_upload' => now()->toDateString(),
            'is_featured' => true,
        ]);
    }

    // =========================================================================
    // 1. AUDIT: LANDING PAGE & PUBLIC ROUTES
    // =========================================================================

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
        // Guest visiting /layanan
        $resLayananGuest = $this->get(route('layanan'));
        $resLayananGuest->assertStatus(200);
        $resLayananGuest->assertSee('Full Wrapping Satin Black');

        // Authenticated user visiting /layanan is redirected to katalog
        $resLayananAuth = $this->actingAs($this->customer)->get(route('layanan'));
        $resLayananAuth->assertRedirect(route('katalog.user'));

        // Visiting katalog
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
        // 127.0.0.1 is allowed
        $resLocal = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        $resLocal->assertStatus(200);

        // Foreign IP is rejected with 403
        $resForeign = $this->call('GET', '/metrics', [], [], [], ['REMOTE_ADDR' => '203.0.113.195']);
        $resForeign->assertStatus(403);
    }

    // =========================================================================
    // 2. AUDIT: KERANJANG BELANJA (CART) & MULTI-TENANT ISOLATION
    // =========================================================================

    public function test_customer_can_view_cart_and_add_items(): void
    {
        $resCart = $this->actingAs($this->customer)->get(route('keranjang.index'));
        $resCart->assertStatus(200);

        // Add package to cart
        $resAdd = $this->actingAs($this->customer)->post(route('keranjang.tambah'), [
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'catatan_custom' => 'Tolong wrapping sudut halus',
        ]);
        $resAdd->assertRedirect(route('keranjang.index'));

        $this->assertDatabaseHas('detail_keranjangs', [
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);
    }

    public function test_cart_validates_maximum_of_3_packages(): void
    {
        $layananC = Layanan::create([
            'nama_layanan' => 'Layanan C',
            'harga' => 1000000,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);
        $layananD = Layanan::create([
            'nama_layanan' => 'Layanan D',
            'harga' => 2000000,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        $this->actingAs($this->customer)->post(route('keranjang.tambah'), [
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
        ]);
        $this->actingAs($this->customer)->post(route('keranjang.tambah'), [
            'id_paket' => $this->layananB->id_layanan,
            'jumlah' => 1,
        ]);
        $this->actingAs($this->customer)->post(route('keranjang.tambah'), [
            'id_paket' => $layananC->id_layanan,
            'jumlah' => 1,
        ]);

        // 4th package addition must be rejected with error toast
        $resFourth = $this->actingAs($this->customer)->post(route('keranjang.tambah'), [
            'id_paket' => $layananD->id_layanan,
            'jumlah' => 1,
        ]);
        $resFourth->assertRedirect(route('keranjang.index'));
        $resFourth->assertSessionHas('toast_error');

        $keranjang = Keranjang::where('id_user', $this->customer->id)->where('status', 'active')->first();
        $this->assertEquals(3, $keranjang->details()->count());
    }

    public function test_cart_quantity_ajax_update(): void
    {
        $keranjang = Keranjang::create([
            'id_user' => $this->customer->id,
            'status' => 'active',
        ]);
        $detail = DetailKeranjang::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);

        $response = $this->actingAs($this->customer)->patchJson(route('keranjang.update', $detail->id_detail), [
            'jumlah' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jumlah' => 2,
        ]);

        $this->assertEquals(9000000, $detail->fresh()->subtotal);
    }

    public function test_cart_item_deletion_and_isolation_security(): void
    {
        // Customer A cart item
        $cartA = Keranjang::create(['id_user' => $this->customer->id, 'status' => 'active']);
        $itemA = DetailKeranjang::create([
            'id_keranjang' => $cartA->id_keranjang,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);

        // Other Customer attempts to delete Customer A's cart item -> 403 Forbidden
        $resHacker = $this->actingAs($this->otherCustomer)->delete(route('keranjang.hapus', $itemA->id_detail));
        $resHacker->assertStatus(403);
        $this->assertDatabaseHas('detail_keranjangs', ['id_detail' => $itemA->id_detail]);

        // Legitimate owner deletes item -> Success
        $resOwner = $this->actingAs($this->customer)->delete(route('keranjang.hapus', $itemA->id_detail));
        $resOwner->assertRedirect(route('keranjang.index'));
        $this->assertDatabaseMissing('detail_keranjangs', ['id_detail' => $itemA->id_detail]);
    }

    public function test_customer_can_empty_cart(): void
    {
        $cart = Keranjang::create(['id_user' => $this->customer->id, 'status' => 'active']);
        DetailKeranjang::create([
            'id_keranjang' => $cart->id_keranjang,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);

        $resEmpty = $this->actingAs($this->customer)->delete(route('keranjang.kosongkan'));
        $resEmpty->assertRedirect(route('keranjang.index'));
        $this->assertEquals(0, $cart->details()->count());
    }

    // =========================================================================
    // 3. AUDIT: PESANAN & INVOICE ACCESS CONTROL
    // =========================================================================

    public function test_customer_pesanan_isolation_and_show(): void
    {
        $pesananA = Pesanan::create([
            'id_user' => $this->customer->id,
            'kode_pesanan' => 'ORD-TEST-001',
            'tanggal_pesan' => now(),
            'status' => Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
            'total_harga' => 4500000,
        ]);
        DetailPesanan::create([
            'id_pesanan' => $pesananA->id_pesanan,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);
        FormPesanan::create([
            'id_pesanan' => $pesananA->id_pesanan,
            'nama_pemesan' => $this->customer->name,
            'no_hp' => '08123456789',
            'alamat_pengiriman' => 'Rogojampi, Banyuwangi',
        ]);

        // Owner can view order
        $resOwner = $this->actingAs($this->customer)->get(route('pesanan.show', $pesananA->id_pesanan));
        $resOwner->assertStatus(200);
        $resOwner->assertSee('ORD-TEST-001');

        // Other customer cannot view order (404/not found for this user)
        $resOther = $this->actingAs($this->otherCustomer)->get(route('pesanan.show', $pesananA->id_pesanan));
        $resOther->assertStatus(404);
    }

    public function test_customer_cannot_download_invoice_until_order_is_confirmed(): void
    {
        $pesanan = Pesanan::create([
            'id_user' => $this->customer->id,
            'kode_pesanan' => 'ORD-TEST-INV',
            'tanggal_pesan' => now(),
            'status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
            'total_harga' => 4500000,
        ]);
        DetailPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);

        // Menunggu pembayaran -> cannot download invoice
        $resInvBlocked = $this->actingAs($this->customer)->get(route('pesanan.invoice', $pesanan->id_pesanan));
        $resInvBlocked->assertRedirect();
        $resInvBlocked->assertSessionHas('toast_error');

        // Update status to dikonfirmasi -> invoice accessible
        $pesanan->update(['status' => Pesanan::STATUS_DIKONFIRMASI]);
        $resInvAllowed = $this->actingAs($this->customer)->get(route('pesanan.invoice', $pesanan->id_pesanan));
        $resInvAllowed->assertStatus(200);
        $resInvAllowed->assertSee('ORD-TEST-INV');
    }

    // =========================================================================
    // 4. AUDIT: ADMIN DASHBOARD & SECURITY ACCESS CONTROL
    // =========================================================================

    public function test_admin_laporan_strictly_forbidden_for_normal_customer(): void
    {
        $resCustomer = $this->actingAs($this->customer)->get(route('admin.laporan'));
        $this->assertTrue(in_array($resCustomer->status(), [403, 302]));

        // Admin can access laporan and view calculations
        $resAdmin = $this->actingAs($this->admin)->get(route('admin.laporan', ['type' => 'hari']));
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Laporan Penjualan Transaksi');
    }

    public function test_filament_admin_panel_strictly_forbidden_for_normal_customer(): void
    {
        $resCustomer = $this->actingAs($this->customer)->get('/admin');
        // Non-admin user should be forbidden (403) or redirected
        $this->assertTrue(in_array($resCustomer->status(), [403, 302]));

        // Admin can access filament dashboard
        $resAdmin = $this->actingAs($this->admin)->get('/admin');
        $resAdmin->assertStatus(200);
    }

    public function test_filament_admin_can_render_bookings_table_with_various_statuses(): void
    {
        Booking::create([
            'booking_code' => 'BK-TEST-ADMIN-01',
            'user_id' => $this->customer->id,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->addDays(2),
            'booking_time' => '10:00',
            'payment_type' => 'dp',
            'status' => \App\Enums\BookingStatus::PENDING,
            'vehicle_name' => 'Honda Civic',
        ]);

        Booking::create([
            'booking_code' => 'BK-TEST-ADMIN-02',
            'user_id' => $this->customer->id,
            'layanan_id' => $this->layananA->id_layanan,
            'booking_date' => now()->addDays(3),
            'booking_time' => '13:00',
            'payment_type' => 'lunas',
            'status' => \App\Enums\BookingStatus::APPROVED,
            'vehicle_name' => 'Toyota Fortuner',
        ]);

        $resAdmin = $this->actingAs($this->admin)->get('/admin/booking');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('BK-TEST-ADMIN-01');
        $resAdmin->assertSee('BK-TEST-ADMIN-02');
    }

    // =========================================================================
    // 5. AUDIT: API ENDPOINTS (RESTful & Sanctum)
    // =========================================================================

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
        // Unauthenticated access to /api/keranjang should return 401
        $resUnauth = $this->getJson('/api/keranjang');
        $resUnauth->assertStatus(401);

        // Authenticated customer via Sanctum
        Sanctum::actingAs($this->customer);

        $resCart = $this->getJson('/api/keranjang');
        $resCart->assertStatus(200);

        $resCartCount = $this->getJson('/api/keranjang/count');
        $resCartCount->assertStatus(200);

        $resNotif = $this->getJson('/api/notifikasi');
        $resNotif->assertStatus(200);

        $resNotifCount = $this->getJson('/api/notifikasi/unread-count');
        $resNotifCount->assertStatus(200);
    }
}
