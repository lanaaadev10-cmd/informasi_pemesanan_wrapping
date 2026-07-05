<?php

namespace App\Http\Requests\Pesanan;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nama_pemesan' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'alamat_pengiriman' => ['required', 'string', 'max:500'],
            'kota_pengiriman' => ['required', 'string', 'max:100'],
            'metode_pembayaran' => ['required', PaymentMethod::validationRule()],
            'keterangan_tambahan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pemesan.required' => 'Nama pemesan harus diisi',
            'no_hp.required' => 'Nomor HP harus diisi',
            'no_hp.regex' => 'Format nomor HP tidak valid',
            'alamat_pengiriman.required' => 'Alamat pengiriman harus diisi',
            'kota_pengiriman.required' => 'Kota pengiriman harus dipilih',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
        ];
    }
}
