<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileApiController extends Controller
{
    /**
     * GET /api/user/profile
     * Get current user profile
     */
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'phone' => $user->phone ?? null,
                'address' => $user->address ?? null,
                'city' => $user->city ?? null,
                'province' => $user->province ?? null,
                'postal_code' => $user->postal_code ?? null,
            ],
        ]);
    }

    /**
     * PATCH /api/user/profile
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'city',
            'province',
            'postal_code',
        ]));

        return response()->json([
            'status' => 'ok',
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'city' => $user->city,
                'province' => $user->province,
                'postal_code' => $user->postal_code,
            ],
        ]);
    }

    /**
     * PATCH /api/user/password
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    /**
     * DELETE /api/user/profile
     * Delete user account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Akun Anda telah dihapus.',
        ]);
    }

    /**
     * GET /api/user/dashboard/stats
     * Get dashboard statistics
     */
    public function dashboardStats()
    {
        $user = Auth::user();

        $totalOrders = Pesanan::where('id_user', $user->id)->count();
        $completedOrders = Pesanan::where('id_user', $user->id)
            ->where('status', 'selesai')
            ->count();
        $pendingOrders = Pesanan::where('id_user', $user->id)
            ->whereIn('status', ['menunggu_verifikasi', 'diverifikasi', 'menunggu_pembayaran', 'menunggu_konfirmasi'])
            ->count();
        $totalSpent = Pesanan::where('id_user', $user->id)->sum('total_harga');

        $recentOrders = Pesanan::where('id_user', $user->id)
            ->orderByDesc('tanggal_pesan')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id_pesanan' => $order->id_pesanan,
                    'kode_pesanan' => $order->kode_pesanan,
                    'tanggal_pesan' => $order->tanggal_pesan,
                    'status' => $order->status,
                    'total_harga' => $order->total_harga,
                ];
            });

        return response()->json([
            'status' => 'ok',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'statistics' => [
                    'total_orders' => $totalOrders,
                    'completed_orders' => $completedOrders,
                    'pending_orders' => $pendingOrders,
                    'total_spent' => $totalSpent,
                ],
                'recent_orders' => $recentOrders,
            ],
        ]);
    }
}
