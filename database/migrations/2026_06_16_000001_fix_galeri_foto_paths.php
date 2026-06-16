<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('galeris')->get()->each(function ($galeri) {
            $foto = $galeri->foto;
            $fixed = false;

            if (str_starts_with($foto, 'public/storage/galeri/')) {
                $foto = substr($foto, strlen('public/storage/galeri/'));
                $fixed = true;
            }

            if (str_starts_with($foto, 'storage/app/public/galeri/')) {
                $foto = substr($foto, strlen('storage/app/public/galeri/'));
                $fixed = true;
            }

            $foto = preg_replace('/\.jpg\.jpg$/i', '.jpg', $foto);
            $foto = preg_replace('/\.png\.png$/i', '.png', $foto);

            if (!str_starts_with($foto, 'galeri/')) {
                $foto = 'galeri/' . $foto;
                $fixed = true;
            }

            if ($fixed || $foto !== $galeri->foto) {
                DB::table('galeris')->where('id_galeri', $galeri->id_galeri)->update(['foto' => $foto]);
            }
        });
    }

    public function down(): void
    {
    }
};
