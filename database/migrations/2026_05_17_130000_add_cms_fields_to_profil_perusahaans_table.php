<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Menambahkan kolom CMS dinamis ke tabel profil_perusahaans.
 *
 * Kolom baru:
 * - visi, misi, sejarah → Halaman "Tentang Kami"
 * - instagram_url, facebook_url, tiktok_url, whatsapp_url → Sosial Media
 * - meta_title, meta_description → SEO
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // Tentang Kami / CMS Dinamis
            $table->text('visi')->nullable()->after('maps_url');
            $table->text('misi')->nullable()->after('visi');
            $table->text('sejarah')->nullable()->after('misi');

            // Sosial Media
            $table->string('instagram_url')->nullable()->after('sejarah');
            $table->string('facebook_url')->nullable()->after('instagram_url');
            $table->string('tiktok_url')->nullable()->after('facebook_url');
            $table->string('whatsapp_url')->nullable()->after('tiktok_url');

            // SEO & Metadata
            $table->string('meta_title')->nullable()->after('whatsapp_url');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'visi',
                'misi',
                'sejarah',
                'instagram_url',
                'facebook_url',
                'tiktok_url',
                'whatsapp_url',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
