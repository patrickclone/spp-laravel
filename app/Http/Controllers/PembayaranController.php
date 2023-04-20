<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Resources\PembayaranResource;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PembayaranController extends Controller
{
    public function history(Request $request)
    {
    	$limit = $request->limit ?? 10;
    	$search = $request->search ?? '';
    	$pembayaran = PembayaranResource::collection(
    		Pembayaran::with('siswa.kelas')->get()->filter(function ($item) use($search){
	    		return str_contains(strtolower($item->siswa->nama), $search) || $search == '';
	    	})->paginate($limit)->withQueryString());
        // $pembayaran = PembayaranResource::collection(
        //     Pembayaran::with(['siswa' => function (Builder $query) use ($search) {
        //         $query->where('nama', 'like', "%$search%");
        //     }])->paginate($limit)->withQueryString()
        // );
        // $pembayaran = PembayaranResource::collection(
        //     Pembayaran::with('siswa.kelas')->whereHas('siswa', function ($query) use($search) {
        //         $query->where('nama', 'like', "%$search%");
        //     })->paginate($limit)->withQueryString()
        // );
        return Inertia::render('Pembayaran/History', ['pembayaran' => $pembayaran]);
    }

    public function pembayaran(Request $request)
    {
        if ($request->nis) {
            $nis = $request->nis;
            $siswa = Siswa::with('kelas')->find($nis);
            $semesterDefault = (date('Y')-$siswa->tahun_masuk)*2 - (date('n')>=7 ? 1 : 0);
            $semester = $request->semester ?? $semesterDefault;
            $semester = $semester > 0 && $semester <= 6 ? $semester : $semesterDefault;
            $range = $semester%2 == 0 ? [1, 6] : [7, 12];
            $tahun = $siswa->tahun_masuk + floor($semester/2);
            $pembayaran = $siswa->pembayaran()->whereBetween('bulan', $range)->where('tahun', $tahun)->orderBy('tahun')->orderBy('bulan')->get();
            $dataPembayaran = [];
            $indexPembayaran = 0;
            $batas_waktu = Carbon::create($tahun, $range[0], 10);
            for ($i = $range[0]; $i <= $range[1]; ++$i) {
                $lunas = false;
                if ($pembayaran->count()) {
                    if ($i == $pembayaran[$indexPembayaran]['bulan']) {
                        $lunas = true;
                        if ($indexPembayaran < $pembayaran->count()-1) {
                            $indexPembayaran++;
                        }
                    }
                }
                $dataPembayaran[] = [
                    'lunas' => $lunas,
                    'bulan' => $i,
                    'tahun' => $tahun,
                    'bulan_dibayar' => $batas_waktu->translatedFormat('F Y'),
                    'batas_waktu' => $batas_waktu->translatedFormat('d F Y'),
                    'tanggal_bayar' => $lunas ? $pembayaran[$indexPembayaran]['tanggal']->translatedFormat('F Y') : null,
                    'keterangan' => $lunas ? 'Lunas' : ($batas_waktu->endOfDay()->isPast() ? 'Melewati Batas Waktu': 'Belum Lunas'),
                    'status_badge' => $lunas ? 'badge-green' : ($batas_waktu->endOfDay()->isPast() ? 'badge-red' : 'badge-yellow'),
                    'nominal' => $lunas ? 'Rp '.number_format($pembayaran[$indexPembayaran]['nominal'],0,',','.').',00' : null
                ];
                $batas_waktu->addMonth();
            }
            $siswa = $siswa->only(['nama', 'nis', 'tahun_masuk', 'no_telp', 'alamat', 'kelas']);
            $siswa['kelas'] = $siswa['kelas']['kelas'];
            $siswa['pembayaran'] = $dataPembayaran;
            return Inertia::render('Pembayaran/Index', ['siswa' => $siswa, 'semester' => $semester]);
        }
        return Inertia::render('Pembayaran/Index');
    }

    public function bayar(Request $request)
    {
        $siswa = Siswa::with('kelas.spp')->find($request->nis);
        $nominal = $siswa->kelas->spp->nominal;
        $siswa->pembayaran()->create([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal' => date('Y-m-d'),
            'nominal' => $siswa->kelas->spp->nominal,
            'id_petugas' => auth()->user()->id,
        ]);
    }
}
