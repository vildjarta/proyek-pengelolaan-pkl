@extends('layout.app')

@section('content')
<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="card shadow border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">
            <i class="fa fa-clipboard me-2"></i> Daftar Penilaian Dosen Penguji
        </h4>
        <a href="{{ route('penilaian.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
            <i class="fa fa-plus me-1"></i> Tambah Penilaian
        </a>
    </div>

    <div class="card-body p-3">
        @if(session('success'))
            <div class="alert alert-success fw-bold">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>NIP</th>
                    <th>Nama Dosen</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul</th>
                    <th>Nilai</th>
                    <th>Jenis Ujian</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penilaian as $p)
                <tr>
                    <td>{{ $p->nip }}</td>
                    <td>{{ $p->nama_dosen }}</td>
                    <td>{{ $p->nama_mahasiswa }}</td>
                    <td>{{ $p->judul }}</td>
                    <td>{{ $p->nilai }}</td>
                    <td>{{ $p->jenis_ujian }}</td>
                    <td>{{ $p->tanggal_ujian }}</td>
                    <td>
                        <a href="{{ route('penilaian.edit', $p->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('penilaian.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data ini?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center fw-bold">Belum ada data penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
