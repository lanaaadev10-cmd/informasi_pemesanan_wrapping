<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            if (!Schema::hasColumn('layanans', 'jenis_layanan')) {
                $table->enum('jenis_layanan', ['paket', 'jasa_pasang', 'custom'])
                    ->default('paket')
                    ->after('tipe_layanan')
                    ->index();
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            // Master Kendaraan
            if (!Schema::hasColumn('bookings', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('vehicle_license')->constrained('vehicle_brands')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'model_id')) {
                $table->foreignId('model_id')->nullable()->after('brand_id')->constrained('vehicle_models')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'vehicle_size')) {
                $table->enum('vehicle_size', ['small', 'medium', 'large', 'xlarge'])->nullable()->after('model_id');
            }
            if (!Schema::hasColumn('bookings', 'source')) {
                $table->enum('source', ['online', 'offline'])->default('online')->after('vehicle_size')->index();
            }

            // Historical Financial Snapshots
            if (!Schema::hasColumn('bookings', 'service_name_snapshot')) {
                $table->string('service_name_snapshot', 255)->nullable()->after('source');
            }
            if (!Schema::hasColumn('bookings', 'base_price_snapshot')) {
                $table->decimal('base_price_snapshot', 15, 2)->nullable()->after('service_name_snapshot');
            }
            if (!Schema::hasColumn('bookings', 'addons_total_snapshot')) {
                $table->decimal('addons_total_snapshot', 15, 2)->default(0)->after('base_price_snapshot');
            }
            if (!Schema::hasColumn('bookings', 'grand_total_snapshot')) {
                $table->decimal('grand_total_snapshot', 15, 2)->nullable()->after('addons_total_snapshot');
            }
            if (!Schema::hasColumn('bookings', 'dp_amount_snapshot')) {
                $table->decimal('dp_amount_snapshot', 15, 2)->nullable()->after('grand_total_snapshot');
            }
            if (!Schema::hasColumn('bookings', 'remaining_balance_snapshot')) {
                $table->decimal('remaining_balance_snapshot', 15, 2)->nullable()->after('dp_amount_snapshot');
            }

            // Jasa Pasang (Deklarasi Material Pelanggan)
            if (!Schema::hasColumn('bookings', 'material_brand')) {
                $table->string('material_brand', 100)->nullable()->after('remaining_balance_snapshot');
            }
            if (!Schema::hasColumn('bookings', 'material_size')) {
                $table->string('material_size', 100)->nullable()->after('material_brand');
            }
            if (!Schema::hasColumn('bookings', 'material_condition')) {
                $table->text('material_condition')->nullable()->after('material_size');
            }
            if (!Schema::hasColumn('bookings', 'material_photo')) {
                $table->string('material_photo', 255)->nullable()->after('material_condition');
            }
            if (!Schema::hasColumn('bookings', 'material_disclaimer_agreed')) {
                $table->boolean('material_disclaimer_agreed')->default(false)->after('material_photo');
            }
            if (!Schema::hasColumn('bookings', 'material_status')) {
                $table->enum('material_status', ['pending', 'approved', 'rejected'])->default('pending')->after('material_disclaimer_agreed');
            }

            // Custom Project & Quotation
            if (!Schema::hasColumn('bookings', 'custom_request_notes')) {
                $table->text('custom_request_notes')->nullable()->after('material_status');
            }
            if (!Schema::hasColumn('bookings', 'custom_reference_photo')) {
                $table->string('custom_reference_photo', 255)->nullable()->after('custom_request_notes');
            }
            if (!Schema::hasColumn('bookings', 'quotation_amount')) {
                $table->decimal('quotation_amount', 15, 2)->nullable()->after('custom_reference_photo');
            }
            if (!Schema::hasColumn('bookings', 'quotation_notes')) {
                $table->text('quotation_notes')->nullable()->after('quotation_amount');
            }
            if (!Schema::hasColumn('bookings', 'quotation_status')) {
                $table->enum('quotation_status', ['none', 'pending', 'sent', 'accepted', 'rejected'])->default('none')->after('quotation_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['model_id']);
            $table->dropColumn([
                'brand_id', 'model_id', 'vehicle_size', 'source',
                'service_name_snapshot', 'base_price_snapshot', 'addons_total_snapshot',
                'grand_total_snapshot', 'dp_amount_snapshot', 'remaining_balance_snapshot',
                'material_brand', 'material_size', 'material_condition', 'material_photo',
                'material_disclaimer_agreed', 'material_status',
                'custom_request_notes', 'custom_reference_photo',
                'quotation_amount', 'quotation_notes', 'quotation_status',
            ]);
        });

        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('jenis_layanan');
        });
    }
};
