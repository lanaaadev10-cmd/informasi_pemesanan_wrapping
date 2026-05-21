<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiApiController extends Controller
{
    /**
     * GET /api/notifikasi
     * List all notifications with pagination
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = $request->get('per_page', 15);
        $type = $request->get('type');

        $query = Notifikasi::where('id_user', $userId);

        if ($type) {
            $query->where('tipe', $type);
        }

        $notifikasi = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'status' => 'ok',
            'data' => $notifikasi->items(),
            'pagination' => [
                'current_page' => $notifikasi->currentPage(),
                'total' => $notifikasi->total(),
                'per_page' => $notifikasi->perPage(),
                'last_page' => $notifikasi->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/notifikasi/unread
     * Get unread notifications only
     */
    public function unread()
    {
        $userId = Auth::id();

        $unreadNotifikasi = Notifikasi::where('id_user', $userId)
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'ok',
            'data' => $unreadNotifikasi,
            'count' => $unreadNotifikasi->count(),
        ]);
    }

    /**
     * GET /api/notifikasi/{id}
     * Get single notification and mark as read
     */
    public function show($id)
    {
        $notifikasi = Notifikasi::where('id_notif', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notifikasi->update(['is_read' => true]);

        if ($notifikasi->id_pesanan) {
            Notifikasi::where('id_user', Auth::id())
                ->where('id_pesanan', $notifikasi->id_pesanan)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'status' => 'ok',
            'data' => $notifikasi,
        ]);
    }

    /**
     * PATCH /api/notifikasi/{id}
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('id_notif', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notifikasi->update(['is_read' => true]);

        if ($notifikasi->id_pesanan) {
            Notifikasi::where('id_user', Auth::id())
                ->where('id_pesanan', $notifikasi->id_pesanan)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Notifikasi ditandai sebagai terbaca.',
        ]);
    }

    /**
     * PATCH /api/notifikasi/mark-all-read
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::where('id_user', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Semua notifikasi ditandai sebagai terbaca.',
        ]);
    }

    /**
     * DELETE /api/notifikasi/{id}
     * Delete notification
     */
    public function destroy($id)
    {
        $notifikasi = Notifikasi::where('id_notif', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$notifikasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * DELETE /api/notifikasi
     * Delete all notifications for current user
     */
    public function deleteAll()
    {
        Notifikasi::where('id_user', Auth::id())->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Semua notifikasi berhasil dihapus.',
        ]);
    }
}
