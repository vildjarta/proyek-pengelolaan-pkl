<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen Penguji</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f8ff;
        }

        .main-content-wrapper {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 60, 130, 0.1);
            margin: 2rem;
            padding: 2rem;
        }

        h1 {
            font-weight: 600;
        }

        thead {
            background-color: #0d6efd;
            color: white;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
        }

        .search-bar {
            max-width: 350px;
        }

        .btn {
            border-radius: 8px;
        }

        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }

        td,
        th {
            white-space: nowrap;
            vertical-align: middle;
            font-size: 0.95rem;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    @include('layout.header')

    {{-- SIDEBAR --}}
    @include('layout.sidebar')

    <div class="main-content-wrapper">
        <div class="content">

            @php
                $user = auth()->user();
            @endphp

            {{-- JUDUL + SEARCH + TAMBAH --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h1>Daftar Dosen Penguji</h1>

                <div class="d-flex gap-2">
                    <form action="{{ route('dosen_penguji.search') }}" method="GET">
                        <input type="text" name="q" class="form-control search-bar"
                            placeholder="Cari dosen...">
                    </form>

                    @if ($user && $user->role === 'koordinator')
                        <a href="{{ route('dosen_penguji.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill"></i> Tambah
                        </a>
                    @endif
                </div>
            </div>

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Dosen</th>
                            <th>Nama Mahasiswa</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($dosenPenguji as $index => $dp)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dp->nip }}</td>
                                <td>{{ $dp->nama_dosen }}</td>
                                <td>{{ $dp->nama_mahasiswa ?? '-' }}</td>
                                <td class="text-start">{{ $dp->email }}</td>
                                <td>{{ $dp->no_hp }}</td>
                                <td>

                                        <a href="{{ route('dosen_penguji.edit', $dp->id_penguji) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('dosen_penguji.destroy', $dp->id_penguji) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted py-4">
                                    Belum ada data dosen penguji
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>
