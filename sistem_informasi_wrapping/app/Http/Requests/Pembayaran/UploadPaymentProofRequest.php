<?php

namespace App\Http\Requests\Pembayaran;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'bukti_transfer' => [
                'required',
                'file',
                'image',
                'max:5120', // 5MB
                'mimes:jpeg,png,jpg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'bukti_transfer.required' => 'Bukti transfer harus diunggah',
            'bukti_transfer.file' => 'File harus berupa file',
            'bukti_transfer.image' => 'File harus berupa gambar',
            'bukti_transfer.max' => 'Ukuran file maksimal 5MB',
            'bukti_transfer.mimes' => 'Format gambar harus JPEG, PNG, atau JPG',
        ];
    }
}
