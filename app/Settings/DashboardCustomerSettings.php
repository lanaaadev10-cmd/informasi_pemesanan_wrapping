<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DashboardCustomerSettings extends Settings
{
    public ?string $dashboard_title = null;
    public ?string $dashboard_subtitle = null;
    public ?string $dashboard_member_title = null;
    public ?string $dashboard_member_desc = null;
    public ?string $dashboard_member_progress = null;
    public ?string $dashboard_member_benefits = null;
    public ?string $dashboard_service_1_title = null;
    public ?string $dashboard_service_1_desc = null;
    public ?string $dashboard_service_1_icon = null;
    public ?string $dashboard_service_2_title = null;
    public ?string $dashboard_service_2_desc = null;
    public ?string $dashboard_service_2_icon = null;
    public ?string $dashboard_service_3_title = null;
    public ?string $dashboard_service_3_desc = null;
    public ?string $dashboard_service_3_icon = null;
    public ?string $dashboard_service_4_title = null;
    public ?string $dashboard_service_4_desc = null;
    public ?string $dashboard_service_4_icon = null;
    public ?string $dashboard_empty_title = null;
    public ?string $dashboard_empty_desc = null;
    public ?string $dashboard_quick_services_title = null;
    public ?string $dashboard_activity_title = null;
    public ?string $dashboard_section_paket_title = null;
    public ?string $dashboard_section_aktivitas_title = null;
    public ?string $dashboard_status_menunggu_label = null;
    public ?string $dashboard_status_diproses_label = null;
    public ?string $dashboard_status_selesai_label = null;
    public ?string $dashboard_empty_state_desc = null;

    public static function group(): string
    {
        return 'dashboard_customer';
    }
}
