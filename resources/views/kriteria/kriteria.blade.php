<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kriteria</title>
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
            color: #0d6efd;
            font-weight: 600;
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #0d6efd !important;
            color: white;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
            transition: 0.2s;
        }

        .btn {
            border-radius: 8px;
        }

        .btn i {
            margin-right: 4px;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        #kriteriaTable td {
            font-size: 0.95rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        @include('layout.header')
    </div>

    <div class="d-flex">
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper">
        <div class="content">

            {{-- Judul dan tombol tambah --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h1 class="mb-0">Daftar Kriteria</h1>

                <a href="{{ route('kriteria.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Kriteria
                </a>
            </div>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel Data --}}
            <div class="table-responsive table-wrapper">
                <table class="table table-bordered align-middle text-center table-sm" id="kriteriaTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 50%;">kriteria</th>
                            <th style="width: 20%;">bobot</th>
                            <th style="width: 25%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kriterias as $index => $kriteria)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kriteria->kriteria }}</td>
                                <td>{{ $kriteria->bobot }}</td>
                                <td>
                                    <a href="{{ route('kriteria.edit', $kriteria->id_kriteria) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    <form action="{{ route('kriteria.destroy', $kriteria->id_kriteria) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash3-fill"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data kriteria</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
