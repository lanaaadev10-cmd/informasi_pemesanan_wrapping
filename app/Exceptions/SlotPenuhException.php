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
        ?string $customMessage = null,
    ) {
        $tgl = \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
        $msg = $customMessage ?: "Slot penuh untuk tanggal {$tgl}. Kapasitas harian ({$maxSlot} kendaraan) telah tercapai. Silakan pilih tanggal lain.";
        parent::__construct($msg);
    }
}
