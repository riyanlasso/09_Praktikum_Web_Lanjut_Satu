<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        // $mahasiswas = Mahasiswa::all(); // Mengambil semua isi tabel
        // $posts = Mahasiswa::orderBy('Nim', 'desc')->paginate(6);
        // return view('mahasiswas.index', compact('mahasiswas'))->with('i', (request()->input('page', 1) - 1) * 5); //mahasiswas adalah nama folder

        $mahasiswas = Mahasiswa::where([
            ['Nama', '!=', Null],
            [function ($query) use ($request) {
                if (($term = $request->term)) {
                    $query->orWhere('Nama', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy("Nim", "asc")
            ->paginate(5);

        $posts = Mahasiswa::orderBy('Nim', 'asc')->paginate(6);
        return view('mahasiswas.index', compact('mahasiswas'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        return view('mahasiswas.create');
    }
    public function store(Request $request)
    {

        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'Tanggal_Lahir' => 'required',
        ]);

        //fungsi eloquent untuk menambah data 
        Mahasiswa::create($request->all());

        //jika data berhasil ditambahkan, akan kembali ke halaman utama 
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    public function edit($Nim)
    {

        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.edit', compact('Mahasiswa'));
    }

    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'Tanggal_Lahir' => 'required',
        ]);

        //fungsi eloquent untuk mengupdate data inputan kita 
        Mahasiswa::find($Nim)->update($request->all());

        //jika data berhasil diupdate, akan kembali ke halaman utama 
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data 
        Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
};
