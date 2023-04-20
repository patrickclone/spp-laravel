<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PembayaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
        	'nama_siswa' => $this->siswa->nama,
        	'nis' => $this->siswa->nis,
        	'id_kelas' => $this->siswa->kelas->id,
        	'kelas' => $this->siswa->kelas->kelas,
        	'bulan' => Carbon::create($this->tahun, $this->bulan, 1)->translatedFormat('F Y'),
        	'tanggal' => $this->tanggal->translatedFormat('d F Y'),
        	'nominal' => 'Rp '.number_format($this->nominal,0,',','.').',00'
        ];
    }
}
