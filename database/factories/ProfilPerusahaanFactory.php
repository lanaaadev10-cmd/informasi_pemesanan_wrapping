<?php

namespace Database\Factories;

use App\Models\ProfilPerusahaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk model ProfilPerusahaan.
 *
 * Digunakan untuk testing dan seeding data dummy.
 */
class ProfilPerusahaanFactory extends Factory
{
    protected $model = ProfilPerusahaan::class;

    public function definition(): array
    {
        return [
            'nama_perusahaan'  => 'Dantie Sticker',
            'deskripsi'        => $this->faker->sentence(15),
            'alamat'           => $this->faker->address(),
            'email'            => $this->faker->companyEmail(),
            'nomor_telepon'    => '08' . $this->faker->numerify('##########'),
            'logo'             => null,
            'maps_url'         => null,
            'visi'             => '<p>' . $this->faker->paragraph(3) . '</p>',
            'misi'             => '<ul><li>' . implode('</li><li>', $this->faker->sentences(4)) . '</li></ul>',
            'sejarah'          => '<p>' . $this->faker->paragraph(5) . '</p>',
            'instagram_url'    => 'https://instagram.com/dantiesticker',
            'facebook_url'     => 'https://facebook.com/dantiesticker',
            'tiktok_url'       => null,
            'whatsapp_url'     => null,
            'meta_title'       => 'Dantie Sticker — Jasa Wrapping Profesional',
            'meta_description' => $this->faker->sentence(12),
        ];
    }
}
