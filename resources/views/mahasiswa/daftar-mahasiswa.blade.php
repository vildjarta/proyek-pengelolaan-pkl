<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mahasiswa - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome & Bootstrap --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .main-content {
            margin-left: 250px;
            margin-top: 80px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .sidebar-closed .main-content {
            margin-left: 80px;
        }

        table {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    {{-- Tarik header & sidebar --}}
    @include('layout.header')
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-users me-2"></i> Daftar Mahasiswa
                </h4>
                <a href="{{ route('mahasiswa.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
                    <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
                </a>
            </div>

            <div class="card-body p-3">
                {{-- ALERT jika ada pesan sukses --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Prodi</th>
                            <th>Angkatan</th>
                            <th>IPK</th>
                            <th>Perusahaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $m)
                        <tr>
                            <td>{{ $m->nim }}</td>
                            <td>{{ $m->nama }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->no_hp ?? '-' }}</td>
                            <td>{{ $m->prodi }}</td>
                            <td>{{ $m->angkatan }}</td>
                            <td>{{ $m->ipk ?? '-' }}</td>
                            <td>{{ $m->perusahaan ?? '-' }}</td>
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
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data mahasiswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.menu-toggle');
            const body = document.body;

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    body.classList.toggle('sidebar-closed');
                });
            }
        });
    </script>

</body>
</html>