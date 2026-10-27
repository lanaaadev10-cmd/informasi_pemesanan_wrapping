<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\DetailKeranjang;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\PembayaranReservasi;
use App\Models\Pesanan;
use App\Models\Reservasi;
use App\Models\User;
use App\Settings\CompanySettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiturAplikasiTest extends TestCase
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

        $this->customer = User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@example.com',
            'email_verified_at' => now(),
        ]);
        $this->customer->assignRole('user');

        $this->otherCustomer = User::factory()->create([
            'name' => 'Siti Customer',
            'email' => 'siti@example.com',
            'email_verified_at' => now(),
        ]);
        $this->otherCustomer->assignRole('user');

        $this->admin = User::factory()->create([
            'name' => 'Admin Dantie',
            'email' => 'admin@dantie.com',
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole('admin');

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
    }

    public function test_customer_can_view_cart_and_add_items(): void
    {
        $resCart = $this->actingAs($this->customer)->get(route('keranjang.index'));
        $resCart->assertStatus(200);

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
        $cartA = Keranjang::create(['id_user' => $this->customer->id, 'status' => 'active']);
        $itemA = DetailKeranjang::create([
            'id_keranjang' => $cartA->id_keranjang,
            'id_paket' => $this->layananA->id_layanan,
            'jumlah' => 1,
            'harga_satuan' => 4500000,
            'subtotal' => 4500000,
        ]);

        $resHacker = $this->actingAs($this->otherCustomer)->delete(route('keranjang.hapus', $itemA->id_detail));
        $resHacker->assertStatus(403);
        $this->assertDatabaseHas('detail_keranjangs', ['id_detail' => $itemA->id_detail]);

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

        $resOwner = $this->actingAs($this->customer)->get(route('pesanan.show', $pesananA->id_pesanan));
        $resOwner->assertStatus(200);
        $resOwner->assertSee('ORD-TEST-001');

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

        $resInvBlocked = $this->actingAs($this->customer)->get(route('pesanan.invoice', $pesanan->id_pesanan));
        $resInvBlocked->assertRedirect();
        $resInvBlocked->assertSessionHas('toast_error');

        $pesanan->update(['status' => Pesanan::STATUS_DIKONFIRMASI]);
        $resInvAllowed = $this->actingAs($this->customer)->get(route('pesanan.invoice', $pesanan->id_pesanan));
        $resInvAllowed->assertStatus(200);
        $resInvAllowed->assertSee('ORD-TEST-INV');
    }

    public function test_booking_invoice_calculates_dynamic_dp_and_remaining_balance(): void
    {
        $harga = 4000000;
        $layanan = Layanan::create([
            'nama_layanan' => 'Full Wrap Premium Titanium',
            'harga' => $harga,
            'tipe_layanan' => 'fix',
            'status' => 'aktif',
        ]);

        $booking = Reservasi::create([
            'booking_code' => 'BKG-INVOICE-TEST',
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_phone' => '081234567890',
            'customer_email' => $this->customer->email,
            'layanan_id' => $layanan->id_layanan,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '09:00',
            'payment_type' => 'dp',
            'vehicle_name' => 'Toyota GR Yaris',
            'status' => BookingStatus::APPROVED,
        ]);

        PembayaranReservasi::create([
            'booking_id' => $booking->id,
            'payment_method' => 'transfer_bank',
            'amount' => 2000000,
            'status' => 'verified',
        ]);

        $booking->load(['layanan', 'payment', 'user']);

        $company = app(CompanySettings::class);
        $renderedHtml = view('customer.booking.invoice-pdf', ['booking' => $booking, 'profil' => $company])->render();
        $this->assertStringContainsString('2.000.000', $renderedHtml);
        $this->assertStringContainsString('Sisa Tagihan di Workshop', $renderedHtml);
        $this->assertStringNotContainsString('100.000', $renderedHtml);

        $responsePdf = $this->actingAs($this->customer)->get(route('booking.invoice', $booking->id));
        $responsePdf->assertStatus(200);
        $this->assertEquals('application/pdf', $responsePdf->headers->get('content-type'));
    }
}
