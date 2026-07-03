<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LayananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_layanan' => $this->id_layanan,
            'nama_layanan' => $this->nama_layanan,
            'deskripsi' => $this->deskripsi,
            'harga' => (float) $this->harga,
            'foto_contoh' => $this->foto_contoh,
            'tipe_layanan' => $this->tipe_layanan,
            'tipe_paket' => $this->tipe_paket,
            'fitur' => $this->fitur,
            'kategori' => $this->kategori,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
