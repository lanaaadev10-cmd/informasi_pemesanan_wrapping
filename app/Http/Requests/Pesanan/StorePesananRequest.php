<?php

namespace App\Http\Requests\Pesanan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk Validasi Formulir Pemesanan Wrapping (Online Checkout).
 *
 * Mengamankan input data customer sebelum pembuatan Pesanan dan FormPesanan.
 */
class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nama_pemesan'        => ['required', 'string', 'max:100'],
            'alamat_pengiriman'   => ['required', 'string'],
            'no_hp'               => ['required', 'string', 'max:20'],
            'model_kendaraan'     => ['required', 'string', 'max:100'],
            'warna_kendaraan'     => ['required', 'string', 'max:100'],
            'lokasi_pengerjaan'   => ['required', 'string', 'in:toko'],
            'jadwal_pengerjaan'   => ['required', 'date', 'after_or_equal:today'],
            'keterangan_tambahan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pemesan.required'      => 'Nama pemesan wajib diisi.',
            'alamat_pengiriman.required' => 'Alamat pengiriman/pemesan wajib diisi.',
            'no_hp.required'             => 'Nomor handphone/WhatsApp wajib diisi.',
            'model_kendaraan.required'   => 'Model kendaraan wajib diisi.',
            'warna_kendaraan.required'   => 'Warna kendaraan wajib diisi.',
            'lokasi_pengerjaan.required' => 'Lokasi pengerjaan wajib dipilih.',
            'jadwal_pengerjaan.required' => 'Jadwal pengerjaan wajib dipilih.',
            'jadwal_pengerjaan.after_or_equal' => 'Jadwal pengerjaan tidak boleh tanggal masa lalu.',
        ];
    }
}
