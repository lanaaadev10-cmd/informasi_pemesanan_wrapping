<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception ketika slot harian sudah penuh.
 *
 * Dilempar oleh SlotKuotaService::assertSlotAvailable()
 * dan ditangani di BookingController / PesananController.
 */
class SlotPenuhException extends Exception
{
    public function __construct(
        public readonly string $date,
        public readonly int $totalUsed,
        public readonly int $maxSlot,
    ) {
        $tgl = \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
        parent::__construct(
            "Slot penuh untuk tanggal {$tgl}. Kapasitas harian ({$maxSlot} kendaraan) telah tercapai dari gabungan booking dan pesanan langsung."
        );
    }
}
