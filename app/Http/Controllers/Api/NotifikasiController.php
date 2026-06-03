<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifikasiResource;
use App\Services\NotifikasiService;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

/**
 * Notifikasi (Notifications) Controller
 * 
 * Endpoints:
 * - GET /api/notifikasi (list user notifications)
 * - POST /api/notifikasi/:id/read (mark as read)
 * - DELETE /api/notifikasi/:id (delete notification)
 */
class NotifikasiController extends Controller
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    /**
     * GET /api/notifikasi
     * List user's notifications with pagination
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $perPage = $request->get('per_page', 20);

        $notifikasis = $this->notifikasiService->getUserNotifications($userId, $perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi berhasil diambil',
            'data' => NotifikasiResource::collection($notifikasis->items()),
            'pagination' => [
                'current_page' => $notifikasis->currentPage(),
                'total' => $notifikasis->total(),
                'per_page' => $notifikasis->perPage(),
                'last_page' => $notifikasis->lastPage(),
            ],
        ], 200);
    }

    /**
     * GET /api/notifikasi/unread-count
     * Get count of unread notifications
     */
    public function unreadCount()
    {
        $userId = auth()->id();
        $count = $this->notifikasiService->getUnreadCount($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah notifikasi belum dibaca berhasil diambil',
            'data' => [
                'unread_count' => $count,
            ],
        ], 200);
    }

    /**
     * POST /api/notifikasi/:id/read
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notifikasi = Notifikasi::findOrFail($id);

            // Authorization: user hanya bisa mark notifikasi miliknya
            if ($notifikasi->id_user !== auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses ke notifikasi ini',
                    'data' => null,
                ], 403);
            }

            $notifikasi = $this->notifikasiService->markAsRead($notifikasi);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil ditandai sebagai sudah dibaca',
                'data' => new NotifikasiResource($notifikasi),
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
     * POST /api/notifikasi/mark-all-read
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::where('id_user', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai sebagai terbaca.',
        ], 200);
    }

    /**
     * DELETE /api/notifikasi/:id
     * Delete notification
     */
    public function destroy($id)
    {
        try {
            $notifikasi = Notifikasi::findOrFail($id);

            // Authorization
            if ($notifikasi->id_user !== auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk menghapus notifikasi ini',
                    'data' => null,
                ], 403);
            }

            $notifikasi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil dihapus',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
