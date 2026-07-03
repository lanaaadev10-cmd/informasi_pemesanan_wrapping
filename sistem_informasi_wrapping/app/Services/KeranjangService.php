<?php

namespace App\Services;

use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk manage shopping cart operations
 * 
 * Handles:
 * - Get or create cart for user
 * - Add/update/remove items
 * - Calculate totals
 * - Clear cart
 */
class KeranjangService
{
    /**
     * Get atau create cart untuk user
     */
    public function getOrCreateCart($userId): Keranjang
    {
        return Keranjang::firstOrCreate(
            ['id_user' => $userId, 'status' => 'active']
        );
    }

    /**
     * Add item ke cart dengan cek duplikasi & max 3 unique items
     * Max 3 paket unique per user
     */
    public function addItem($userId, $idLayanan, $quantity, $customData = null)
    {
        $keranjang = $this->getOrCreateCart($userId);
        $layanan = Layanan::findOrFail($idLayanan);

        // Cek apakah item sudah di cart
        $existingItem = $keranjang->details()
            ->where('id_layanan', $idLayanan)
            ->first();

        // Cek max items
        $itemCount = $keranjang->details()->count();
        if (!$existingItem && $itemCount >= $this->getMaxItems()) {
            throw new \Exception('Maksimal hanya ' . $this->getMaxItems() . ' paket dalam keranjang.');
        }

        DB::beginTransaction();
        try {
            if ($existingItem) {
                // Update quantity jika sudah ada
                $newQuantity = $existingItem->quantity + $quantity;
                $existingItem->update([
                    'quantity' => $newQuantity,
                    'subtotal' => $newQuantity * $existingItem->harga_satuan,
                ]);
            } else {
                // Buat item baru
                DetailKeranjang::create([
                    'id_keranjang' => $keranjang->id_keranjang,
                    'id_layanan' => $idLayanan,
                    'quantity' => $quantity,
                    'harga_satuan' => $layanan->harga,
                    'subtotal' => $quantity * $layanan->harga,
                    'custom_data' => $customData,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $keranjang->fresh()->load('details.layanan');
    }

    /**
     * Update quantity item di cart
     */
    public function updateItem($userId, $idDetailKeranjang, $quantity)
    {
        $keranjang = $this->getOrCreateCart($userId);
        
        $item = $keranjang->details()
            ->findOrFail($idDetailKeranjang);

        $newSubtotal = $quantity * $item->harga_satuan;
        $item->update([
            'quantity' => $quantity,
            'subtotal' => $newSubtotal,
        ]);

        return $keranjang->fresh()->load('details.layanan');
    }

    /**
     * Remove item dari cart
     */
    public function removeItem($userId, $idDetailKeranjang)
    {
        $keranjang = $this->getOrCreateCart($userId);
        
        $keranjang->details()
            ->findOrFail($idDetailKeranjang)
            ->delete();

        return $keranjang->fresh()->load('details.layanan');
    }

    /**
     * Clear semua item di cart
     */
    public function clearCart($userId)
    {
        $keranjang = $this->getOrCreateCart($userId);
        $keranjang->details()->delete();

        return $keranjang->fresh()->load('details.layanan');
    }

    /**
     * Get cart summary
     */
    public function getCartSummary($userId): array
    {
        $keranjang = $this->getOrCreateCart($userId);
        $details = $keranjang->load('details');

        $totalItems = $details->details->count();
        $totalHarga = $details->details->sum('subtotal');

        return [
            'total_items' => $totalItems,
            'total_harga' => (float) $totalHarga,
            'details' => $details->details,
        ];
    }

    /**
     * Check apakah cart empty
     */
    public function isCartEmpty($userId): bool
    {
        $keranjang = $this->getOrCreateCart($userId);
        return $keranjang->details()->count() === 0;
    }

    /**
     * Check apakah paket sudah ada di cart
     */
    public function isItemInCart($userId, $idLayanan): bool
    {
        $keranjang = $this->getOrCreateCart($userId);
        return $keranjang->details()
            ->where('id_layanan', $idLayanan)
            ->exists();
    }

    /**
     * Get cart count (unique items)
     */
    public function getCartItemCount($userId): int
    {
        $keranjang = $this->getOrCreateCart($userId);
        return $keranjang->details()->count();
    }

    /**
     * Get max items allowed
     */
    public function getMaxItems(): int
    {
        return config('app-settings.cart.max_items', 3);
    }

    /**
     * Check apakah bisa menambah item baru
     */
    public function canAddMoreItems($userId): bool
    {
        return $this->getCartItemCount($userId) < $this->getMaxItems();
    }
}
