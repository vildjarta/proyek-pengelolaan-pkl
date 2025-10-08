@extends('layout.app')

@section('title', 'Daftar Tempat Pengujian')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Daftar Tempat Pengujian</h1>

    {{-- Tombol Tambah Tempat --}}
    <div class="text-end mb-3">
        <a href="{{ route('tempat_pengujian.create') }}" class="btn btn-primary">
            + Tambah Tempat
        </a>
    </div>

    {{-- Tabel Data Tempat --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 120px;">ID Tempat</th>
                        <th>Nama Tempat</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tempat as $t)
                        <tr>
                            <td class="text-center">{{ $t->id_tempat }}</td>
                            <td>{{ $t->tempat }}</td>
                            <td class="text-center">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('tempat_pengujian.edit', $t->id_tempat) }}"
                                    class="btn btn-warning btn-sm">Edit</a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('tempat_pengujian.destroy', $t->id_tempat) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin mau hapus tempat ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Belum ada data tempat pengujian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
