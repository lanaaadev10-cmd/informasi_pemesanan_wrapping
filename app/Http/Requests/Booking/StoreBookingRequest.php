<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Siapkan data sebelum divalidasi (autofill dari sesi jika kosong).
     */
    protected function prepareForValidation(): void
    {
        if (!$this->filled('customer_name') && Auth::check()) {
            $this->merge(['customer_name' => Auth::user()->name]);
        }
        if (!$this->filled('customer_phone') && Auth::check()) {
            $phone = Auth::user()->no_hp ?: (Auth::user()->phone ?: '081234567890');
            $this->merge(['customer_phone' => $phone]);
        }
    }

    /**
     * Aturan validasi untuk pembuatan booking baru.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'              => 'required|string|max:150',
            'customer_phone'             => 'required|string|max:30',
            'customer_email'             => 'nullable|email|max:150',
            'layanan_id'                 => 'required|exists:layanans,id_layanan',
            'booking_date'               => 'required|date|after_or_equal:today',
            'booking_time'               => 'nullable|string|max:10',
            'payment_type'               => 'required|in:dp,lunas',
            'payment_method'             => 'nullable|string|max:50',
            'brand_id'                   => 'nullable|exists:vehicle_brands,id',
            'model_id'                   => 'nullable|exists:vehicle_models,id',
            'vehicle_size'               => 'nullable|in:small,medium,large,xlarge',
            'vehicle_name'               => 'nullable|string|max:150',
            'vehicle_color'              => 'nullable|string|max:100',
            'vehicle_license'            => 'nullable|string|max:50',
            'addons'                     => 'nullable|array',
            'addons.*'                   => 'exists:addons,id',
            'material_brand'             => 'nullable|string|max:100',
            'material_size'              => 'nullable|string|max:100',
            'material_condition'         => 'nullable|string|max:500',
            'material_photo'             => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'material_disclaimer_agreed' => 'nullable',
            'custom_request_notes'       => 'nullable|string|max:1000',
            'custom_reference_photo'     => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'notes'                      => 'nullable|string|max:1000',
            'proof_file'                 => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    /**
     * Validasi lanjutan: Cegah booking otomatis bernilai Rp 0 untuk paket custom quote tanpa harga.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('layanan_id')) {
                $layanan = \App\Models\Layanan::find($this->layanan_id);
                if ($layanan && (float) $layanan->harga <= 0) {
                    $validator->errors()->add(
                        'layanan_id',
                        'Layanan ini bertipe penawaran kustom (Custom Quote). Silakan lakukan konsultasi estimasi biaya dan spesifikasi terlebih dahulu via WhatsApp sebelum mengajukan reservasi mandiri.'
                    );
                }
            }
        });
    }

    /**
     * Pesan kustom untuk validasi booking.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required'      => 'Nama lengkap wajib diisi.',
            'customer_phone.required'     => 'Nomor WhatsApp wajib diisi.',
            'layanan_id.required'         => 'Pilih paket layanan yang diinginkan.',
            'booking_date.required'       => 'Pilih tanggal booking yang tersedia.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
        ];
    }
}
