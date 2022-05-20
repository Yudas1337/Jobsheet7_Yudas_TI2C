<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaRequest;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function searchMahasiswa(Request $request)
    {
        $search     = $request->search;
        $mahasiswa  = Mahasiswa::whereLike('nim', $search)
            ->orWhereLike('nama', $search)
            ->orWhereLike('jurusan', $search)
            ->orWhereLike('email', $search)
            ->orWhereLike('alamat', $search)
            ->orWhereLike('tanggal_lahir', $search)
            ->paginate(3);
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $mahasiswa = Mahasiswa::with('kelas')->latest('nim')->paginate(3);
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(MahasiswaRequest $request)
    {
        $mahasiswa          = new Mahasiswa;
        $mahasiswa->nim     = $request->nim;
        $mahasiswa->nama    = $request->nama;
        $mahasiswa->jurusan = $request->jurusan;
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->kelas;

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($Nim)
    {
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        return view('mahasiswa.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($nim)
    {
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswa.edit', compact('mahasiswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(MahasiswaRequest $request, $nim)
    {
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $mahasiswa->nim     = $request->nim;
        $mahasiswa->nama    = $request->nama;
        $mahasiswa->jurusan = $request->jurusan;
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->kelas;

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($Nim)
    {
        Mahasiswa::getByNim($Nim)->delete();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
}
