<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_notifikasi' => $this->id_notifikasi,
            'id_pesanan' => $this->id_pesanan,
            'id_user' => $this->id_user,
            'tipe' => $this->tipe,
            'judul' => $this->judul,
            'isi' => $this->isi,
            'status' => $this->status,
            'is_read' => (bool) $this->is_read,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
