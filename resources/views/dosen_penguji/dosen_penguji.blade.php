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

        .search-bar {
            max-width: 350px;
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

        #dosenTable th,
        #dosenTable td {
            white-space: nowrap;
            /* Biar teks tidak turun ke baris baru */
            vertical-align: middle;
        }

        #dosenTable td {
            font-size: 0.95rem;
            padding: 0.5rem;
        }

        /* Batasi lebar maksimum tabel agar tidak keluar layar */
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
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

    <div class="main-content-wrapper">
        <div class="content">
            {{-- Baris atas: Judul, Pencarian, dan Tombol Tambah --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h1 class="mb-0">Daftar Dosen Penguji</h1>

                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('dosen_penguji.search') }}" method="get" class="d-flex align-items-center">
                        <div class="input-group search-bar">
                            <input type="text" id="searchInput" name="q" class="form-control"
                                placeholder="Cari dosen...">
                            {{-- Jika ingin tombol search aktif, tinggal hapus komentar di bawah --}}
                            {{-- <button class="btn btn-primary" id="searchBtn">
                            <i class="bi bi-search"></i> Search
                        </button> --}}
                        </div>
                    </form>

                    <a href="{{ route('dosen_penguji.create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-person-plus-fill"></i> Tambah
                    </a>
                </div>
            </div>

            {{-- Tabel Data Dosen Penguji --}}
            <div class="table-responsive table-wrapper">
                <table class="table table-bordered align-middle text-center table-sm" id="dosenTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">NIP</th>
                            <th style="width: 25%;">Nama Dosen</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">No HP</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosenPenguji as $index => $dp)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dp->nip }}</td>
                                <td class="text-start">{{ $dp->nama_dosen }}</td>
                                <td class="text-start">{{ $dp->email }}</td>
                                <td>{{ $dp->no_hp }}</td>
                                <td>
                                    <a href="{{ route('dosen_penguji.edit', $dp->id_penguji) }}"
                                        class="btn btn-warning btn-sm btn-action">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('dosen_penguji.destroy', $dp->id_penguji) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin mau hapus data ini?')">
                                            <i class="bi bi-trash3-fill"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data dosen penguji</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        // Pencarian sederhana di sisi client
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#dosenTable tbody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>

</html>
