<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // ─── KATALOG PAGE CMS ───────────────────────────────────
            $table->text('katalog_hero_title')->nullable()->after('dark_mode')->comment('Katalog page hero title');
            $table->text('katalog_hero_desc')->nullable()->comment('Katalog page hero description');
            $table->text('katalog_intro_text')->nullable()->comment('Katalog intro/intro text');
            $table->text('katalog_empty_state_title')->nullable()->comment('Empty state title');
            $table->text('katalog_empty_state_desc')->nullable()->comment('Empty state description');
            $table->text('katalog_feature_1_title')->nullable()->comment('Feature 1 title');
            $table->text('katalog_feature_1_desc')->nullable()->comment('Feature 1 description');
            $table->text('katalog_feature_2_title')->nullable()->comment('Feature 2 title');
            $table->text('katalog_feature_2_desc')->nullable()->comment('Feature 2 description');
            $table->text('katalog_feature_3_title')->nullable()->comment('Feature 3 title');
            $table->text('katalog_feature_3_desc')->nullable()->comment('Feature 3 description');
            $table->text('katalog_feature_4_title')->nullable()->comment('Feature 4 title');
            $table->text('katalog_feature_4_desc')->nullable()->comment('Feature 4 description');

            // ─── GALERI PAGE CMS ───────────────────────────────────
            $table->text('galeri_hero_title')->nullable()->comment('Galeri page hero title');
            $table->text('galeri_hero_desc')->nullable()->comment('Galeri page hero description');
            $table->text('galeri_intro_text')->nullable()->comment('Galeri intro text');
            $table->text('galeri_empty_state_title')->nullable()->comment('Galeri empty state title');
            $table->text('galeri_empty_state_desc')->nullable()->comment('Galeri empty state description');

            // ─── PESANAN PAGE CMS ───────────────────────────────────
            $table->text('pesanan_page_title_all')->nullable()->comment('Pesanan page title - all tab');
            $table->text('pesanan_page_desc_all')->nullable()->comment('Pesanan page description - all tab');
            $table->string('pesanan_filter_semua_label', 100)->nullable()->comment('Filter button label - semua');
            $table->string('pesanan_filter_berjalan_label', 100)->nullable()->comment('Filter button label - berjalan');
            $table->string('pesanan_filter_selesai_label', 100)->nullable()->comment('Filter button label - selesai');
            $table->text('pesanan_empty_state_title')->nullable()->comment('Pesanan empty state title');
            $table->text('pesanan_empty_state_desc')->nullable()->comment('Pesanan empty state description');
            $table->string('pesanan_new_order_button_label', 100)->nullable()->comment('Mulai pesanan baru button label');

            // ─── KERANJANG PAGE CMS ──────────────────────────────────
            $table->text('keranjang_hero_text')->nullable()->comment('Keranjang hero text');
            $table->text('keranjang_title')->nullable()->comment('Keranjang page title');
            $table->text('keranjang_subtitle')->nullable()->comment('Keranjang page subtitle');
            $table->text('keranjang_empty_title')->nullable()->comment('Keranjang empty title');
            $table->text('keranjang_empty_desc')->nullable()->comment('Keranjang empty description');
            $table->text('keranjang_warranty_title')->nullable()->comment('Warranty section title');
            $table->text('keranjang_warranty_desc')->nullable()->comment('Warranty section description');
            $table->string('keranjang_service_charge_label', 100)->nullable()->comment('Service charge label');
            $table->string('keranjang_service_charge_amount', 50)->nullable()->comment('Service charge amount (e.g., 10%)');
            $table->string('keranjang_checkout_button_label', 100)->nullable()->comment('Proceed to checkout button label');

            // ─── CHECKOUT PAGE CMS ───────────────────────────────────
            $table->string('checkout_step_1_label', 100)->nullable()->comment('Checkout step 1 label');
            $table->string('checkout_step_2_label', 100)->nullable()->comment('Checkout step 2 label');
            $table->string('checkout_step_3_label', 100)->nullable()->comment('Checkout step 3 label');
            $table->string('checkout_step_4_label', 100)->nullable()->comment('Checkout step 4 label');
            $table->text('checkout_step2_title')->nullable()->comment('Checkout step 2 form title');
            $table->text('checkout_step2_subtitle')->nullable()->comment('Checkout step 2 form subtitle');
            $table->text('checkout_step3_title')->nullable()->comment('Checkout step 3 review title');
            $table->text('checkout_step3_subtitle')->nullable()->comment('Checkout step 3 review subtitle');
            $table->string('checkout_warranty_badge_1', 100)->nullable()->comment('Warranty badge 1 text');
            $table->string('checkout_warranty_badge_2', 100)->nullable()->comment('Warranty badge 2 text');
            $table->string('checkout_confirm_button_label', 100)->nullable()->comment('Confirm order button label');

            // ─── PROFIL/TENTANG KAMI PAGE CMS ───────────────────────────
            $table->text('profil_pillar_1_title')->nullable()->comment('Three Pillars section - pillar 1 title');
            $table->text('profil_pillar_1_desc')->nullable()->comment('Three Pillars section - pillar 1 description');
            $table->text('profil_pillar_2_title')->nullable()->comment('Three Pillars section - pillar 2 title');
            $table->text('profil_pillar_2_desc')->nullable()->comment('Three Pillars section - pillar 2 description');
            $table->text('profil_pillar_3_title')->nullable()->comment('Three Pillars section - pillar 3 title');
            $table->text('profil_pillar_3_desc')->nullable()->comment('Three Pillars section - pillar 3 description');

            // ─── DASHBOARD SECTION HEADERS ─────────────────────────────
            $table->text('dashboard_quick_services_title')->nullable()->comment('Quick Services section title');
            $table->text('dashboard_activity_title')->nullable()->comment('Activity section title');
        });
    }

    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // Katalog
            $table->dropColumn([
                'katalog_hero_title', 'katalog_hero_desc', 'katalog_intro_text',
                'katalog_empty_state_title', 'katalog_empty_state_desc',
                'katalog_feature_1_title', 'katalog_feature_1_desc',
                'katalog_feature_2_title', 'katalog_feature_2_desc',
                'katalog_feature_3_title', 'katalog_feature_3_desc',
                'katalog_feature_4_title', 'katalog_feature_4_desc',
            ]);

            // Galeri
            $table->dropColumn([
                'galeri_hero_title', 'galeri_hero_desc', 'galeri_intro_text',
                'galeri_empty_state_title', 'galeri_empty_state_desc',
            ]);

            // Pesanan
            $table->dropColumn([
                'pesanan_page_title_all', 'pesanan_page_desc_all',
                'pesanan_filter_semua_label', 'pesanan_filter_berjalan_label', 'pesanan_filter_selesai_label',
                'pesanan_empty_state_title', 'pesanan_empty_state_desc', 'pesanan_new_order_button_label',
            ]);

            // Keranjang
            $table->dropColumn([
                'keranjang_hero_text', 'keranjang_title', 'keranjang_subtitle',
                'keranjang_empty_title', 'keranjang_empty_desc',
                'keranjang_warranty_title', 'keranjang_warranty_desc',
                'keranjang_service_charge_label', 'keranjang_service_charge_amount', 'keranjang_checkout_button_label',
            ]);

            // Checkout
            $table->dropColumn([
                'checkout_step_1_label', 'checkout_step_2_label', 'checkout_step_3_label', 'checkout_step_4_label',
                'checkout_step2_title', 'checkout_step2_subtitle', 'checkout_step3_title', 'checkout_step3_subtitle',
                'checkout_warranty_badge_1', 'checkout_warranty_badge_2', 'checkout_confirm_button_label',
            ]);

            // Profil
            $table->dropColumn([
                'profil_pillar_1_title', 'profil_pillar_1_desc',
                'profil_pillar_2_title', 'profil_pillar_2_desc',
                'profil_pillar_3_title', 'profil_pillar_3_desc',
            ]);

            // Dashboard
            $table->dropColumn([
                'dashboard_quick_services_title', 'dashboard_activity_title',
            ]);
        });
    }
};
