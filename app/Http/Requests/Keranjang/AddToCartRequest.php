<?php

namespace App\Http\Requests\Keranjang;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id_layanan' => ['required', 'exists:layanans,id_layanan'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'custom_data' => ['nullable', 'json'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_layanan.required' => 'Layanan harus dipilih',
            'id_layanan.exists' => 'Layanan tidak ditemukan',
            'quantity.required' => 'Jumlah harus diisi',
            'quantity.min' => 'Jumlah minimal 1',
            'quantity.max' => 'Jumlah maksimal 100',
            'custom_data.json' => 'Format data tidak valid',
        ];
    }
}
