<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $search = $request->search ?? '';
        $data = Siswa::with('kelas')->where('tahun_masuk', '>', angkatanSekarang()-3)->get()->filter(function ($item) use($search){
                return str_contains(strtolower($item->nama), $search) || $search == '';
        })->paginate($limit)->withQueryString()->through(fn($siswa) => [
            'nis' => $siswa->nis,
            'nama' => $siswa->nama,
            'kelas' => $siswa->kelas->kelas,
            'id_kelas' => $siswa->kelas->id,
            'no_telp' => $siswa->no_telp,
            'alamat' => $siswa->alamat,
        ]);
        // dd($data);
        return Inertia::render('Siswa/Index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Siswa/Tambah', ['kelas' => Kelas::where('tahun_angkatan', '>', angkatanSekarang()-3)->get(['id', 'kelas'])]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request)
    {
        $siswa = Siswa::create($request->validated());
        return redirect('/siswa')->with('alert', $siswa ? ['success', 'Data berhasil ditambah'] : ['error', 'Data gagal ditambah']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        return Inertia::render('Siswa/Tambah', [
            'edit' => true,
            'siswa' => $siswa,
            'kelas' => Kelas::where('tahun_angkatan', '>', angkatanSekarang()-3)->get(['id', 'kelas'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        return redirect('/siswa')->with('swal', $siswa->delete() ? ['success', 'Data berhasil dihapus'] : ['error', 'Data gagal dihapus']);
    }

    public function delete(Request $request)
    {
        return redirect('/siswa')->with('swal', Siswa::destroy($request->nis) ? ['success', 'Data berhasil dihapus'] : ['error', 'Data gagal dihapus']);
    }
}
