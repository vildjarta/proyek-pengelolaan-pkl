@extends('layout.app') {{-- Ganti sesuai nama layout utama kamu --}}

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Daftar Pengujian</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('pengujian.create') }}" class="btn btn-primary">+ Tambah Pengujian</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Penguji</th>
                    <th>Tempat Pengujian</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengujian as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->dosen->nama_dosen ?? '-' }}</td>
                        <td>{{ $p->tempat->tempat ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $p->jam }}</td>
                        <td>
                            <a href="{{ route('pengujian.edit', $p->id_pengujian) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pengujian.destroy', $p->id_pengujian) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data pengujian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
