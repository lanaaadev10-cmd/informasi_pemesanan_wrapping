<?php

namespace Tests\Feature;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemilihanBarangKeranjangTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
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

    public function test_user_can_select_one_of_three_items(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $response = $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [$item2->id_detail],
        ]);

        $response->assertOk()
            ->assertJson([
                'success'            => true,
                'selected_ids'       => [$item2->id_detail],
                'selected_count'     => 1,
                'total_cart_count'   => 3,
                'subtotal_raw'       => 300000,
                'subtotal_formatted' => 'Rp 300.000',
            ]);
    }

    public function test_user_can_select_two_of_three_items(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $response = $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [$item1->id_detail, $item2->id_detail],
        ]);

        $response->assertOk()
            ->assertJson([
                'success'            => true,
                'selected_ids'       => [$item1->id_detail, $item2->id_detail],
                'selected_count'     => 2,
                'total_cart_count'   => 3,
                'subtotal_raw'       => 400000,
                'subtotal_formatted' => 'Rp 400.000',
            ]);
    }

    public function test_user_can_select_all_items(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $response = $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [$item1->id_detail, $item2->id_detail, $item3->id_detail],
        ]);

        $response->assertOk()
            ->assertJson([
                'success'            => true,
                'selected_count'     => 3,
                'total_cart_count'   => 3,
                'subtotal_raw'       => 600000,
                'subtotal_formatted' => 'Rp 600.000',
            ]);
    }

    public function test_user_can_deselect_all_items(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $response = $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [],
        ]);

        $response->assertOk()
            ->assertJson([
                'success'            => true,
                'selected_ids'       => [],
                'selected_count'     => 0,
                'total_cart_count'   => 3,
                'subtotal_raw'       => 0,
                'subtotal_formatted' => 'Rp 0',
            ]);
    }

    public function test_subtotal_and_count_reflect_in_cart_view(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);
        session(['selected_cart_' . $this->userA->id => [$item2->id_detail]]);

        $response = $this->actingAs($this->userA)->get(route('keranjang.index'));

        $response->assertOk();
        $response->assertSee('badge-selected-count', false);
        $response->assertSee('Rp 300.000');
        $response->assertSee('bg-emerald-500', false);
        $response->assertSee('ph-check', false);
    }

    public function test_quantity_update_and_item_deletion_preserve_valid_selection_state(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $this->actingAs($this->userA)->postJson(route('keranjang.pilihan'), [
            'selected_ids' => [$item1->id_detail, $item2->id_detail],
        ]);

        $updateResponse = $this->actingAs($this->userA)->patchJson(route('keranjang.update', $item1->id_detail), [
            'jumlah' => 3,
        ]);

        $updateResponse->assertOk()
            ->assertJson([
                'success' => true,
                'jumlah'  => 3,
            ]);

        $item1->refresh();
        $this->assertEquals(3, $item1->jumlah);
        $this->assertEquals(300000, (float) $item1->subtotal);

        $deleteResponse = $this->actingAs($this->userA)->delete(route('keranjang.hapus', $item2->id_detail));
        $deleteResponse->assertRedirect(route('keranjang.index'));

        $currentSelected = session('selected_cart_' . $this->userA->id, []);
        $this->assertContains($item1->id_detail, $currentSelected);
        $this->assertNotContains($item2->id_detail, $currentSelected);
    }

    public function test_ui_renders_green_checkmarks_toolbar_and_responsive_controls(): void
    {
        [$keranjang, $item1, $item2, $item3] = $this->createThreeItemsCart($this->userA);

        $response = $this->actingAs($this->userA)->get(route('keranjang.index'));

        $response->assertOk();
        $response->assertSee('btn-select-all', false);
        $response->assertSee('Batalkan Semua Pilihan');
        $response->assertSee('checkbox-indicator-' . $item1->id_detail, false);
        $response->assertSee('checkbox-indicator-' . $item2->id_detail, false);
        $response->assertSee('checkbox-indicator-' . $item3->id_detail, false);
        $response->assertSee('btn-checkout-submit', false);
    }
}
