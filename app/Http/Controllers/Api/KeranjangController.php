<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeranjangResource;
use App\Http\Requests\Keranjang\AddToCartRequest;
use App\Http\Requests\Keranjang\UpdateCartItemRequest;
use App\Services\KeranjangService;
use Illuminate\Http\Request;

/**
 * Keranjang (Shopping Cart) Controller
 * 
 * Endpoints:
 * - GET /api/keranjang (get cart)
 * - POST /api/keranjang/item (add to cart)
 * - PUT /api/keranjang/item/:id (update quantity)
 * - DELETE /api/keranjang/item/:id (remove item)
 * - DELETE /api/keranjang/clear (clear cart)
 */
class KeranjangController extends Controller
{
    public function __construct(
        protected KeranjangService $keranjangService,
    ) {}

    /**
     * GET /api/keranjang
     * Get current user's cart
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $keranjang = $this->keranjangService->getOrCreateCart($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Keranjang berhasil diambil',
            'data' => new KeranjangResource($keranjang->load('details.layanan')),
        ], 200);
    }

    /**
     * POST /api/keranjang/item
     * Add item to cart
     */
    public function addItem(AddToCartRequest $request)
    {
        try {
            $userId = auth()->id();
            $keranjang = $this->keranjangService->addItem(
                $userId,
                $request->id_layanan,
                $request->quantity,
                $request->custom_data,
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil ditambahkan ke keranjang',
                'data' => new KeranjangResource($keranjang),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    /**
     * PUT /api/keranjang/item/:idDetailKeranjang
     * Update cart item quantity
     */
    public function updateItem(UpdateCartItemRequest $request, $idDetailKeranjang)
    {
        try {
            $userId = auth()->id();
            $keranjang = $this->keranjangService->updateItem(
                $userId,
                $idDetailKeranjang,
                $request->quantity,
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Item keranjang berhasil diupdate',
                'data' => new KeranjangResource($keranjang),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    /**
     * DELETE /api/keranjang/item/:idDetailKeranjang
     * Remove item from cart
     */
    public function removeItem($idDetailKeranjang)
    {
        try {
            $userId = auth()->id();
            $keranjang = $this->keranjangService->removeItem($userId, $idDetailKeranjang);

            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil dihapus dari keranjang',
                'data' => new KeranjangResource($keranjang),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    /**
     * DELETE /api/keranjang/clear
     * Clear entire cart
     */
    public function clear()
    {
        try {
            $userId = auth()->id();
            $keranjang = $this->keranjangService->clearCart($userId);

            return response()->json([
                'status' => 'success',
                'message' => 'Keranjang berhasil dikosongkan',
                'data' => new KeranjangResource($keranjang),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    /**
     * GET /api/keranjang/check/:idLayanan
     * Check if specific item is in cart
     */
    public function checkItemInCart($idLayanan)
    {
        try {
            $userId = auth()->id();
            $inCart = $this->keranjangService->isItemInCart($userId, $idLayanan);
            $canAdd = $this->keranjangService->canAddMoreItems($userId);
            $cartCount = $this->keranjangService->getCartItemCount($userId);
            $maxItems = $this->keranjangService->getMaxItems();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'in_cart' => $inCart,
                    'can_add' => $canAdd,
                    'cart_count' => $cartCount,
                    'max_items' => $maxItems,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/keranjang/count
     * Get cart item count
     */
    public function getCount()
    {
        try {
            $userId = auth()->id();
            $count = $this->keranjangService->getCartItemCount($userId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'count' => $count,
                    'max_items' => $this->keranjangService->getMaxItems(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
