<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Keranjang;
use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service untuk manage order operations
 * 
 * Handles:
 * - Create order dari cart (checkout)
 * - Update order status dengan validasi
 * - Cancel order (jika boleh)
 * - Get order dengan relations
 */
class PesananService
{
    protected KeranjangService $keranjangService;

    public function __construct(KeranjangService $keranjangService)
    {
        $this->keranjangService = $keranjangService;
    }

    /**
     * Create order dari cart (checkout process)
     */
    public function checkout($userId, array $data): Pesanan
    {
        // Validasi cart tidak kosong
        if ($this->keranjangService->isCartEmpty($userId)) {
            throw new \Exception('Keranjang kosong. Tambahkan item terlebih dahulu.');
        }

        $keranjang = $this->keranjangService->getOrCreateCart($userId);
        $keranjangDetails = $keranjang->details()->with('layanan')->get();

        DB::beginTransaction();
        try {
            // Generate unique order code
            $kodePesanan = 'ORD-' . date('YmdHis') . '-' . Str::random(6);

            // Calculate total price
            $totalHarga = $keranjangDetails->sum('subtotal');

            // 1. Create order
            $pesanan = Pesanan::create([
                'id_user' => $userId,
                'kode_pesanan' => $kodePesanan,
                'tanggal_pesan' => now(),
                'status' => OrderStatus::MENUNGGU_KONFIRMASI_ADMIN->value,
                'total_harga' => $totalHarga,
            ]);

            // 2. Create order details dari cart items
            foreach ($keranjangDetails as $cartItem) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_layanan' => $cartItem->id_layanan,
                    'quantity' => $cartItem->quantity,
                    'harga_satuan' => $cartItem->harga_satuan,
                    'subtotal' => $cartItem->subtotal,
                    'custom_data' => $cartItem->custom_data,
                ]);
            }

            // 3. Create order form
            FormPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_pemesan' => $data['nama_pemesan'],
                'no_hp' => $data['no_hp'],
                'alamat_pengiriman' => $data['alamat_pengiriman'],
                'kota_pengiriman' => $data['kota_pengiriman'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'keterangan_tambahan' => $data['keterangan_tambahan'] ?? null,
            ]);

            // 4. Clear cart
            $this->keranjangService->clearCart($userId);

            DB::commit();

            // 5. Emit event (listeners akan handle email, notification, etc)
            event(new OrderCreated($pesanan->fresh()->load([
                'details.layanan',
                'form',
                'user',
            ])));

            return $pesanan->fresh()->load(['details.layanan', 'form', 'user']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order status dengan validasi state machine
     */
    public function updateStatus(Pesanan $pesanan, string $newStatus, ?string $catatan = null): Pesanan
    {
        $currentStatus = OrderStatus::from($pesanan->status);
        $newStatusEnum = OrderStatus::from($newStatus);

        // Validate transition
        if (!in_array($newStatusEnum, array_map(
            fn($status) => $status,
            $currentStatus->validTransitions()
        ))) {
            throw new \Exception(
                "Transisi tidak diizinkan dari {$currentStatus->label()} ke {$newStatusEnum->label()}"
            );
        }

        DB::beginTransaction();
        try {
            $pesanan->update([
                'status' => $newStatus,
                'catatan_admin' => $catatan,
            ]);

            // Emit event berdasarkan status baru
            $this->emitStatusChangeEvent($pesanan, $newStatusEnum);

            DB::commit();

            return $pesanan->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel order (hanya jika bisa)
     */
    public function cancelOrder(Pesanan $pesanan, ?string $alasan = null): Pesanan
    {
        $currentStatus = OrderStatus::from($pesanan->status);

        if (!$currentStatus->canBeCancelled()) {
            throw new \Exception(
                "Order tidak dapat dibatalkan pada status {$currentStatus->label()}"
            );
        }

        return $this->updateStatus(
            $pesanan,
            OrderStatus::DITOLAK->value,
            $alasan ?? 'Dibatalkan oleh customer'
        );
    }

    /**
     * Get order dengan semua relations
     */
    public function getOrderDetails($pesananId): Pesanan
    {
        return Pesanan::with([
            'details.layanan',
            'form',
            'pembayaran',
            'notifikasis',
            'user',
        ])->findOrFail($pesananId);
    }

    /**
     * Get user orders dengan pagination
     */
    public function getUserOrders($userId, $perPage = 10, ?string $status = null)
    {
        $query = Pesanan::where('id_user', $userId)
            ->with(['details.layanan', 'form', 'pembayaran']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderByDesc('tanggal_pesan')->paginate($perPage);
    }

    /**
     * Emit event sesuai status baru untuk trigger listeners
     */
    protected function emitStatusChangeEvent(Pesanan $pesanan, OrderStatus $status): void
    {
        $pesananWithRelations = $pesanan->fresh()->load([
            'details.layanan',
            'form',
            'user',
            'pembayaran',
        ]);

        match ($status) {
            OrderStatus::MENUNGGU_PEMBAYARAN => event(new \App\Events\OrderConfirmed($pesananWithRelations)),
            OrderStatus::SEDANG_DIPROSES => event(new \App\Events\PaymentVerified($pesananWithRelations)),
            OrderStatus::SELESAI => event(new \App\Events\OrderCompleted($pesananWithRelations)),
            OrderStatus::DITOLAK => event(new \App\Events\OrderRejected($pesananWithRelations)),
            default => null,
        };
    }
}
