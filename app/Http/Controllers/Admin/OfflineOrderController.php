<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OfflineOrderController extends Controller
{
    public function index()
    {
        $orders = Pesanan::where('order_source', 'offline')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.offline-orders.index', compact('orders'));
    }

    public function create()
    {
        $packages = Layanan::all();
        return view('admin.offline-orders.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'whatsapp_number'   => 'required|string|max:20',
            'address'           => 'nullable|string',
            'catatan_pesanan'   => 'nullable|string',
            'tanggal_pemesanan' => 'required|date',
            'status_pembayaran' => 'required|in:lunas,menunggu',
            'metode_pembayaran' => 'required_if:status_pembayaran,lunas|nullable|string|max:100',
            'total_harga'       => 'required|numeric|min:0',
            'id_paket'          => 'required|exists:layanans,id_layanan',
            'username'          => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = null;
            if (!empty($validated['username'])) {
                $user = User::where('name', $validated['username'])
                    ->orWhere('email', $validated['username'])
                    ->first();
            }

            $order = Pesanan::create([
                'id_user'         => $user?->id,
                'kode_pesanan'    => 'OFL-' . strtoupper(Str::random(8)),
                'tanggal_pesan'   => $validated['tanggal_pemesanan'],
                'status'          => $validated['status_pembayaran'] === 'lunas'
                    ? Pesanan::STATUS_SELESAI
                    : Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
                'total_harga'     => $validated['total_harga'],
                'order_source'    => 'offline',
                'customer_name'   => $validated['customer_name'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'address'         => $validated['address'],
                'catatan_admin'   => $validated['catatan_pesanan'] ?? null,
            ]);

            $paket = Layanan::findOrFail($validated['id_paket']);
            DetailPesanan::create([
                'id_pesanan'   => $order->id_pesanan,
                'id_paket'     => $paket->id_layanan,
                'jumlah'       => 1,
                'harga_satuan' => $paket->harga,
                'subtotal'     => $validated['total_harga'],
            ]);

            if ($validated['status_pembayaran'] === 'lunas') {
                Pembayaran::create([
                    'id_pesanan'       => $order->id_pesanan,
                    'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'tunai',
                    'jumlah_bayar'     => $validated['total_harga'],
                    'status'           => 'sudah_dibayar',
                    'tgl_bayar'        => now(),
                    'verifikasi_admin' => 'diverifikasi',
                ]);
            }
        });

        return redirect()->route('admin.offline.orders.index')
            ->with('toast_success', 'Pesanan offline berhasil dibuat.');
    }

    public function edit($id)
    {
        $order = Pesanan::where('order_source', 'offline')
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $packages = Layanan::all();
        return view('admin.offline-orders.edit', compact('order', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $order = Pesanan::where('order_source', 'offline')
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'whatsapp_number'   => 'required|string|max:20',
            'address'           => 'nullable|string',
            'catatan_pesanan'   => 'nullable|string',
            'tanggal_pemesanan' => 'required|date',
            'total_harga'       => 'required|numeric|min:0',
        ]);

        $order->update([
            'customer_name'     => $validated['customer_name'],
            'whatsapp_number'   => $validated['whatsapp_number'],
            'address'           => $validated['address'],
            'tanggal_pesan'     => $validated['tanggal_pemesanan'],
            'total_harga'       => $validated['total_harga'],
            'catatan_admin'     => $validated['catatan_pesanan'] ?? null,
        ]);

        return redirect()->route('admin.offline.orders.index')
            ->with('toast_success', 'Pesanan offline berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $order = Pesanan::where('order_source', 'offline')
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $order->delete();

        return redirect()->route('admin.offline.orders.index')
            ->with('toast_success', 'Pesanan offline berhasil dihapus.');
    }
}
