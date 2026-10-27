<?php

namespace Tests\Feature;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemilihanBarangKeranjangCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Layanan $layanan1;
    protected Layanan $layanan2;
    protected Layanan $layanan3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);

        $this->userA = User::factory()->create([
            'name'              => 'Pengguna Alpha',
            'email'             => 'alpha@wrapping.local',
            'email_verified_at' => now(),
        ]);
        $this->userA->assignRole('user');

        $this->userB = User::factory()->create([
            'name'              => 'Pengguna Beta',
            'email'             => 'beta@wrapping.local',
            'email_verified_at' => now(),
        ]);
        $this->userB->assignRole('user');

        $this->layanan1 = Layanan::create([
            'nama_layanan'   => 'Produk A - Full Wrap Satin',
            'deskripsi'      => 'Full body wrapping satin finish',
            'harga'          => 100000,
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'wrapping',
            'jenis_layanan'  => 'paket',
            'estimasi_waktu' => '2 Hari',
            'is_active'      => true,
        ]);

        $this->layanan2 = Layanan::create([
            'nama_layanan'   => 'Produk B - Kaca Film Ceramic',
            'deskripsi'      => 'Kaca film tolak panas tinggi',
            'harga'          => 150000,
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'window-film',
            'jenis_layanan'  => 'paket',
            'estimasi_waktu' => '1 Hari',
            'is_active'      => true,
        ]);

        $this->layanan3 = Layanan::create([
            'nama_layanan'   => 'Produk C - Decal Striping Sport',
            'deskripsi'      => 'Striping custom decal racing',
            'harga'          => 200000,
            'tipe_layanan'   => 'Mobil',
            'kategori'       => 'mobil',
            'tipe_paket'     => 'custom',
            'jenis_layanan'  => 'paket',
            'estimasi_waktu' => '1 Hari',
            'is_active'      => true,
        ]);
    }

    protected function createThreeItemsCart(User $user): array
    {
        $keranjang = Keranjang::create([
            'id_user' => $user->id,
            'status'  => 'active',
        ]);

        $item1 = DetailKeranjang::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_paket'     => $this->layanan1->id_layanan,
            'jumlah'       => 1,
            'harga_satuan' => 100000,
            'subtotal'     => 100000,
        ]);

        $item2 = DetailKeranjang::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_paket'     => $this->layanan2->id_layanan,
            'jumlah'       => 2,
            'harga_satuan' => 150000,
            'subtotal'     => 300000,
        ]);

        $item3 = DetailKeranjang::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_paket'     => $this->layanan3->id_layanan,
            'jumlah'       => 1,
            'harga_satuan' => 200000,
            'subtotal'     => 200000,
        ]);

        return [$keranjang, $item1, $item2, $item3];
    }

    public function test_unselected_items_remain_in_cart_after_checkout(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $checkoutData = [
            'nama_pemesan'        => 'Pengguna Alpha',
            'no_hp'               => '081234567890',
            'alamat_pengiriman'   => 'Jl. Sudirman No. 12, Jakarta',
            'model_kendaraan'     => 'Porsche 911 GT3',
            'warna_kendaraan'     => 'Hitam Doff',
            'lokasi_pengerjaan'   => 'toko',
            'jadwal_pengerjaan'   => now()->addDays(2)->toDateString(),
            'selected_ids'        => [$item2->id_detail],
        ];

        $response = $this->actingAs($this->userA)->post(route('pesanan.checkout.store'), $checkoutData);
        $response->assertRedirect();

        $pesanan = Pesanan::where('id_user', $this->userA->id)->latest('id_pesanan')->first();
        $this->assertNotNull($pesanan);
        $this->assertEquals(300000, (float) $pesanan->total_harga);

        $this->assertCount(1, $pesanan->details);
        $this->assertEquals($this->layanan2->id_layanan, $pesanan->details->first()->id_paket);

        $keranjang->refresh();
        $this->assertEquals('active', $keranjang->status);
        $this->assertCount(2, $keranjang->details);
        $this->assertTrue($keranjang->details->contains('id_detail', $item1->id_detail));
        $this->assertTrue($keranjang->details->contains('id_detail', $item3->id_detail));
        $this->assertFalse($keranjang->details->contains('id_detail', $item2->id_detail));
    }

    public function test_user_a_and_user_b_have_strictly_isolated_carts_and_selections(): void
    {
        [$keranjangA, $itemA1, $itemA2, $itemA3] = $this->createThreeItemsCart($this->userA);

        $keranjangB = Keranjang::create([
            'id_user' => $this->userB->id,
            'status'  => 'active',
        ]);
        $itemB1 = DetailKeranjang::create([
            'id_keranjang' => $keranjangB->id_keranjang,
            'id_paket'     => $this->layanan1->id_layanan,
            'jumlah'       => 1,
            'harga_satuan' => 100000,
            'subtotal'     => 100000,
        ]);

        $response = $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [$itemB1->id_detail],
        ]);

        $response->assertOk();
        $this->assertEmpty($response->json('selected_ids'));
        $this->assertEquals(0, $response->json('selected_count'));

        $tamperedCheckout = [
            'nama_pemesan'        => 'Pengguna Alpha',
            'no_hp'               => '081234567890',
            'alamat_pengiriman'   => 'Jl. Merdeka No. 1',
            'model_kendaraan'     => 'BMW M4',
            'warna_kendaraan'     => 'Putih',
            'lokasi_pengerjaan'   => 'toko',
            'jadwal_pengerjaan'   => now()->addDays(2)->toDateString(),
            'selected_ids'        => [$itemB1->id_detail],
        ];

        $checkoutResponse = $this->actingAs($this->userA)->post(route('pesanan.checkout.store'), $tamperedCheckout);
        $checkoutResponse->assertSessionHas('toast_error');

        $this->assertDatabaseMissing('pesanans', [
            'id_user' => $this->userA->id,
        ]);

        $this->assertDatabaseHas('detail_keranjangs', [
            'id_detail' => $itemB1->id_detail,
        ]);
    }

    public function test_user_cannot_checkout_without_selecting_any_items(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);
        session(['selected_cart_' . $this->userA->id => []]);

        $formResponse = $this->actingAs($this->userA)->get(route('pesanan.checkout.form', ['selected' => []]));
        $formResponse->assertRedirect(route('keranjang.index'))
            ->assertSessionHas('toast_error');

        $checkoutData = [
            'nama_pemesan'        => 'Pengguna Alpha',
            'no_hp'               => '081234567890',
            'alamat_pengiriman'   => 'Jl. Sudirman No. 12',
            'model_kendaraan'     => 'Civic Type R',
            'warna_kendaraan'     => 'Merah',
            'lokasi_pengerjaan'   => 'toko',
            'jadwal_pengerjaan'   => now()->addDays(2)->toDateString(),
            'selected_ids'        => [],
        ];

        $submitResponse = $this->actingAs($this->userA)->post(route('pesanan.checkout.store'), $checkoutData);
        $submitResponse->assertSessionHas('toast_error');
    }

    public function test_cart_becomes_checked_out_when_all_items_are_processed(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $checkoutData = [
            'nama_pemesan'        => 'Pengguna Alpha',
            'no_hp'               => '081234567890',
            'alamat_pengiriman'   => 'Jl. Sudirman No. 12',
            'model_kendaraan'     => 'Civic Type R',
            'warna_kendaraan'     => 'Merah',
            'lokasi_pengerjaan'   => 'toko',
            'jadwal_pengerjaan'   => now()->addDays(2)->toDateString(),
            'selected_ids'        => [$item1->id_detail, $item2->id_detail, $item3->id_detail],
        ];

        $response = $this->actingAs($this->userA)->post(route('pesanan.checkout.store'), $checkoutData);
        $response->assertRedirect();

        $keranjang->refresh();
        $this->assertEquals('checked_out', $keranjang->status);
        $this->assertCount(0, $keranjang->details);
    }
}
