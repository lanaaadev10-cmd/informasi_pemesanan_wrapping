<?php

namespace App\Models\Traits;

trait HasCompanyCms
{
    /**
     * Mendapatkan daftar kolom fillable khusus CMS.
     */
    public function getCmsFillable(): array
    {
        return [
            // Homepage
            'home_title',
            'home_subtitle',
            'home_hero_image',
            'home_prof_title',
            'home_prof_subtitle',
            'home_catalog_title',
            'home_catalog_subtitle',
            'home_recent_title',
            'home_recent_subtitle',
            'home_cta_title',
            'home_cta_subtitle',

            // Auth Pages
            'login_title',
            'login_subtitle',
            'login_image',
            'login_form_title',
            'login_form_subtitle',
            'register_title',
            'register_subtitle',
            'register_image',
            'register_form_title',
            'register_form_subtitle',

            // About (Profil)
            'about_hero_title',
            'about_hero_subtitle',
            'stats_experience',
            'stats_projects',
            'stats_satisfaction',
            'stats_support',
            'about_feature_title',
            'about_feature_desc',
            'about_feature_image',
            'about_feature_list',

            // Process & Other
            'visi',
            'misi',
            'sejarah',
            'dashboard_title',
            'dashboard_subtitle',
            'step_1_title',
            'step_1_desc',
            'step_2_title',
            'step_2_desc',
            'step_3_title',
            'step_3_desc',
            'step_4_title',
            'step_4_desc',
            'katalog_title',
            'katalog_subtitle',
            'galeri_title',
            'galeri_subtitle',
            'testimonis_json',
            'auth_badge',
            'footer_copyright',
        ];
    }
}
