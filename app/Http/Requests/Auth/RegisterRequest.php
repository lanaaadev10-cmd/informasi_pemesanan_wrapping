<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'not_regex:/[0-9]/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:9',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).{9,20}$/',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)8[1-9][0-9]{7,10}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal :min karakter.',
            'name.not_regex' => 'Nama tidak boleh mengandung angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid. Contoh: nama@email.com',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal :min karakter.',
            'password.max' => 'Kata sandi maksimal :max karakter.',
            'password.confirmed' => 'Kata sandi tidak cocok, silakan periksa kembali.',
            'password.regex' => 'Kata sandi harus mengandung minimal 1 huruf kapital, 1 huruf kecil, dan 1 simbol.',
            'phone.regex' => 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx',
        ];
    }
}
