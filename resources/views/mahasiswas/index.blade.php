@extends('mahasiswas.layout')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left mt-2">
            <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
        </div>
        <div class="float-right my-2">
            <a class="btn btn-success" href="{{ route('mahasiswas.create') }}"> Input Mahasiswa</a>
        </div>
    </div>
</div>

<!-- //fitur cari -->
<div>
    <div class="mx-auto pull-right">
        <div class="">
            <form action="{{ route('mahasiswas.index') }}" method="GET" role="search">

                <div class="input-group">
                    <span class="input-group-btn mr-5 mt-1">
                        <button class="btn btn-info" type="submit" value="Cari" title="Search mahasiswa">Cari</button>
                        <span class="fas fa-search"></span>
                        </button>
                    </span>
                    <input type="text" class="form-control mr-2" name="term" placeholder="Search nama" id="term">
                    <a href="{{ route('mahasiswas.index') }}" class=" mt-1">
                        <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" title="Refresh page">Refresh</button>
                            <span class="fas fa-sync-alt"></span>
                            </button>
                        </span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- sampai sini -->
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
    <tr>
        <th>Nim</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Jurusan</th>
        <th>No_Handphone</th>
        <th>Email</th>
        <th>Tanggal_Lahir</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($paginate as $Mahasiswa)
    <tr>

        <td>{{ $Mahasiswa->Nim }}</td>
        <td>{{ $Mahasiswa->Nama }}</td>
        <td>{{ $Mahasiswa->kelas->nama_kelas}}</td>
        <td>{{ $Mahasiswa->Jurusan }}</td>
        <td>{{ $Mahasiswa->No_Handphone }}</td>
        <td>{{ $Mahasiswa->Email }}</td>
        <td>{{ $Mahasiswa->Tanggal_Lahir }}</td>
        <td>
            <form action="{{ route('mahasiswas.destroy',$Mahasiswa->Nim) }}" method="POST">

                <a class="btn btn-info" href="{{ route('mahasiswas.show',$Mahasiswa->Nim) }}">Show</a>

                <a class="btn btn-primary" href="{{ route('mahasiswas.edit',$Mahasiswa->Nim) }}">Edit</a> @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
{{$paginate->links('pagination::bootstrap-4')}}
@endsection