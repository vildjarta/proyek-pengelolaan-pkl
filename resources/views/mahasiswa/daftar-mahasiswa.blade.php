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
            <i class="fa fa-users me-2"></i> Daftar Mahasiswa
        </h4>
        {{-- Tombol Tambah Mahasiswa --}}
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
            <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
        </a>
    </div>

    <div class="card-body p-3">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>IPK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswa as $m)
                <tr>
                    <td>{{ $m->nim }}</td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->email }}</td>
                    <td>{{ $m->no_hp }}</td>
                    <td>{{ $m->prodi }}</td>
                    <td>{{ $m->angkatan }}</td>
                    <td>{{ $m->ipk }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.edit', $m->id_mahasiswa) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data ini?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection