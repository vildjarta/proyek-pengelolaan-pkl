<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen Penguji</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="d-flex">
        {{-- header --}}
        @include('layout.header')
    </div>

    <div class="d-flex">
        {{-- sidebar --}}
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper p-4">
        <div class="content">
            <h1 class="mb-4 text-center">Daftar Dosen Penguji</h1>

            {{-- Tombol Tambah --}}
            <div class="text-end mb-3">
                <a href="{{ route('dosen_penguji.create') }}" class="btn btn-primary">+ Tambah Dosen Penguji</a>
            </div>

            {{-- Tabel Data Dosen Penguji --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>ID Penguji</th>
                            <th>NIP</th>
                            <th>Nama Dosen</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosenPenguji as $index => $dp)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dp->id_penguji }}</td>
                                <td>{{ $dp->nip }}</td>
                                <td>{{ $dp->nama_dosen }}</td>
                                <td>{{ $dp->email }}</td>
                                <td>{{ $dp->no_hp }}</td>
                                <td>
                                    <a href="{{ route('dosen_penguji.edit', $dp->id_penguji) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <form action="{{ route('dosen_penguji.destroy', $dp->id_penguji) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin mau hapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data dosen penguji</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
