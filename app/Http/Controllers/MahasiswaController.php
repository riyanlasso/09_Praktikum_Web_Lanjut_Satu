<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
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
        $pagination = Mahasiswa::orderBy('Nim', 'asc')->paginate(5);
        $mahasiswas = Mahasiswa::with('kelas')->where([
            ['Nama', '!=', Null],
            [function ($query) use ($request) {
                if (($term = $request->term)) {
                    $query->orWhere('Nama', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy("Nim", "asc")
            ->paginate(5);
        return view('mahasiswas.index', ['mahasiswas' => $mahasiswas, 'paginate' => $pagination]);


        // $mahasiswas = Mahasiswa::where([
        //     ['Nama', '!=', Null],
        //     [function ($query) use ($request) {
        //         if (($term = $request->term)) {
        //             $query->orWhere('Nama', 'LIKE', '%' . $term . '%')->get();
        //         }
        //     }]
        // ])
        //     ->orderBy("Nim", "asc")
        //     ->paginate(5);

        // $posts = Mahasiswa::orderBy('Nim', 'asc')->paginate(6);
        // return view('mahasiswas.index', compact('mahasiswas'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswas.create', ['kelas' => $kelas]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Tanggal_Lahir' => 'required',
            'Kelas' => 'required',
            'Email' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);

        $mahasiswa = new Mahasiswa();
        $mahasiswa->Nim = $request->get('Nim');
        $mahasiswa->Nama = $request->get('Nama');
        $mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');
        $mahasiswa->Email = $request->get('Email');
        $mahasiswa->kelas_id = $request->get('Kelas');
        $mahasiswa->Jurusan = $request->get('Jurusan');
        $mahasiswa->No_Handphone = $request->get('No_Handphone');
        $mahasiswa->save();

        $kelas = new Kelas();
        $kelas->id = $request->get('Kelas');

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();
        return redirect()->route('mahasiswas.index')->with('success', 'Mahasiswa Berhasil Ditambahkan');

        //melakukan validasi data
        // $request->validate([
        //     'Nim' => 'required',
        //     'Nama' => 'required',
        //     'Kelas' => 'required',
        //     'Jurusan' => 'required',
        //     'No_Handphone' => 'required',
        //     'Email' => 'required',
        //     'Tanggal_Lahir' => 'required',
        // ]);

        // //fungsi eloquent untuk menambah data 
        // Mahasiswa::create($request->all());

        // //jika data berhasil ditambahkan, akan kembali ke halaman utama 
        // return redirect()->route('mahasiswas.index')
        //     ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', ['Mahasiswa' => $Mahasiswa]);
    }

    public function edit($Nim)
    {

        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::with('kelas')->where('Nim', $Nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswas.edit', compact('Mahasiswa', 'kelas'));
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

        $mahasiswa = Mahasiswa::with('kelas')->where('Nim', $Nim)->first();
        $mahasiswa->Nama = $request->get('Nama');
        $mahasiswa->Tanggal_Lahir = $request->get('Tanggal_Lahir');
        $mahasiswa->Email = $request->get('Email');
        $mahasiswa->kelas_id = $request->get('Kelas');
        $mahasiswa->Jurusan = $request->get('Jurusan');
        $mahasiswa->No_Handphone = $request->get('No_Handphone');
        $mahasiswa->save();

        $kelas = new Kelas();
        $kelas->id = $request->get('Kelas');

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();
        return redirect()->route('mahasiswas.index')->with('success', 'Mahasiswa Berhasil Diupdate');

        // //fungsi eloquent untuk mengupdate data inputan kita 
        // Mahasiswa::find($Nim)->update($request->all());

        // //jika data berhasil diupdate, akan kembali ke halaman utama 
        // return redirect()->route('mahasiswas.index')
        //     ->with('success', 'Mahasiswa Berhasil Diupdate');
    }
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data 
        Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
};
