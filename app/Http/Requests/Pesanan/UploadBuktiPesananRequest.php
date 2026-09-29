<?php

namespace App\Http\Requests\Pesanan;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiPesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'metode_pembayaran' => ['required', 'string'],
            'bukti_transfer'    => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti_transfer.required'    => 'Foto atau bukti transfer wajib diunggah.',
            'bukti_transfer.image'       => 'Berkas bukti pembayaran harus berupa gambar.',
            'bukti_transfer.mimes'       => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'bukti_transfer.max'         => 'Ukuran bukti transfer maksimal 5MB.',
        ];
    }
}
