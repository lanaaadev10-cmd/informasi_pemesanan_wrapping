<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormPesananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_form_pesanan' => $this->id_form_pesanan,
            'id_pesanan' => $this->id_pesanan,
            'nama_pemesan' => $this->nama_pemesan,
            'no_hp' => $this->no_hp,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'kota_pengiriman' => $this->kota_pengiriman,
            'metode_pembayaran' => $this->metode_pembayaran,
            'keterangan_tambahan' => $this->keterangan_tambahan,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
