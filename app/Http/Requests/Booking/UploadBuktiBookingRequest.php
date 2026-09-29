<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiBookingRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk upload bukti transfer booking.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proof_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'payment_method' => 'nullable|string|max:50',
        ];
    }

    /**
     * Pesan kustom untuk validasi upload bukti booking.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'proof_file.required' => 'Unggah file bukti pembayaran terlebih dahulu.',
            'proof_file.mimes'    => 'Format bukti pembayaran harus JPG, PNG, atau PDF.',
            'proof_file.max'      => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ];
    }
}
