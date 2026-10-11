<?php

use App\Enums\PaymentType;
use App\Models\Reservasi;

test('enum PaymentType mengembalikan nilai dan label skema pembayaran yang benar', function () {
    expect(PaymentType::DP->value)->toBe('dp');
    expect(PaymentType::DP->label())->toBe('Down Payment (DP)');

    expect(PaymentType::LUNAS->value)->toBe('lunas');
    expect(PaymentType::LUNAS->label())->toBe('Lunas (Cash)');
});

test('perhitungan uang muka standar (DP 50%) dan sisa tagihan pada Reservasi dihitung tepat', function () {
    $reservasi = new Reservasi();
    $reservasi->base_price_snapshot = 4000000;

    expect($reservasi->total_price)->toBe(4000000.0);
    expect($reservasi->dp_amount)->toBe(2000000.0);
    expect($reservasi->remaining_amount)->toBe(2000000.0);
});

test('nilai snapshot keuangan memiliki prioritas lebih tinggi dibanding rumus default', function () {
    $reservasi = new Reservasi();
    $reservasi->base_price_snapshot = 5000000;
    $reservasi->grand_total_snapshot = 6000000;
    $reservasi->dp_amount_snapshot = 2500000;
    $reservasi->remaining_balance_snapshot = 3500000;

    expect($reservasi->total_price)->toBe(6000000.0);
    expect($reservasi->dp_amount)->toBe(2500000.0);
    expect($reservasi->remaining_amount)->toBe(3500000.0);
});

test('sisa tagihan tidak boleh bernilai negatif ketika DP melebihi total', function () {
    $reservasi = new Reservasi();
    $reservasi->base_price_snapshot = 1000000;
    $reservasi->dp_amount_snapshot = 1500000;

    expect($reservasi->remaining_amount)->toBe(0.0);
});

test('url notifikasi WhatsApp merapikan format nomor telepon lokal ke standar internasional', function () {
    $reservasi = new Reservasi();
    $reservasi->customer_phone = '081234567890';
    $reservasi->customer_name = 'Budi Santoso';
    $reservasi->service_name_snapshot = 'Full Wrapping Mobil';
    $reservasi->status = \App\Enums\BookingStatus::PENDING;

    $url = $reservasi->whatsapp_notification_url;

    expect($url)->toContain('https://api.whatsapp.com/send?phone=6281234567890');
    expect($url)->toContain('Budi+Santoso');
});
