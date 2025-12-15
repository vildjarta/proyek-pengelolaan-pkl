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
        /* Style tetap sama seperti sebelumnya */
        :root {
            --header-height: 60px;
            --sidebar-width: 285px;
            --sidebar-collapsed: 70px;
        }

        body { margin: 0; }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 0;
            padding: 20px;
            transition: margin-left 0.3s ease;
            box-sizing: border-box;
            min-height: calc(100vh - var(--header-height));
            position: relative;
            background: transparent;
        }

        body.sidebar-closed .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        .main-scroll {
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
            padding-bottom: 40px;
        }

        .page-header { margin-bottom: 12px; }
        table { font-size: 14px; }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 12px;
            }
            .main-scroll {
                height: calc(100vh - var(--header-height));
            }
        }

        .sidebar .dropdown-menu {
            position: static !important;
            float: none !important;
            inset: auto !important;
            transform: none !important;
            min-width: 0 !important;
            display: block !important;
            list-style: none;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .sidebar .dropdown-menu.collapsed {
            display: none !important;
        }
    </style>
</head>
<body>

    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content">
        <div class="main-scroll">
            <div class="container-fluid">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="fa fa-users me-2"></i> Daftar Mahasiswa
                        </h4>
                        
                        {{-- LOGIKA 1: Tombol Tambah hanya untuk Koordinator --}}
                        {{-- Ganti 'koordinator' sesuai value di database Anda --}}
                        @if(Auth::user()->role == 'koordinator')
                            <a href="{{ route('mahasiswa.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
                                <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
                            </a>
                        @endif
                    </div>

                    <div class="card-body p-3">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center mb-0">
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
                                        
                                        {{-- LOGIKA 2: Kolom Header Aksi hanya muncul untuk Koordinator --}}
                                        @if(Auth::user()->role == 'koordinator')
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswa as $m)
                                    <tr>
                                        <td>{{ $m->nim }}</td>
                                        <td class="text-start">{{ $m->nama }}</td>
                                        <td class="text-start">{{ $m->email }}</td>
                                        <td>{{ $m->no_hp ?? '-' }}</td>
                                        <td>{{ $m->prodi }}</td>
                                        <td>{{ $m->angkatan }}</td>
                                        <td>{{ $m->ipk ?? '-' }}</td>
                                        <td class="text-start">{{ $m->perusahaan ?? '-' }}</td>
                                        
                                        {{-- LOGIKA 3: Tombol Edit & Hapus hanya untuk Koordinator --}}
                                        @if(Auth::user()->role == 'koordinator')
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
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        {{-- LOGIKA 4: Sesuaikan Colspan agar tabel tetap rapi --}}
                                        <td colspan="{{ Auth::user()->role == 'koordinator' ? 9 : 8 }}" class="text-center text-muted">
                                            Belum ada data mahasiswa.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.menu-toggle');
            const bodyEl = document.body;

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    bodyEl.classList.toggle('sidebar-closed');
                    try {
                        localStorage.setItem('app_sidebar_closed', bodyEl.classList.contains('sidebar-closed') ? '1' : '0');
                    } catch(e) {}
                });
                try {
                    const stored = localStorage.getItem('app_sidebar_closed');
                    if (stored === '1') bodyEl.classList.add('sidebar-closed');
                } catch(e) {}
            }

            function syncHeights() {
                const root = getComputedStyle(document.documentElement);
                const headerHeight = parseInt(root.getPropertyValue('--header-height')) || 60;
                const mainScroll = document.querySelector('.main-scroll');
                if (mainScroll) mainScroll.style.height = (window.innerHeight - headerHeight) + 'px';
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) sidebar.style.height = (window.innerHeight - headerHeight) + 'px';
            }

            window.addEventListener('resize', syncHeights);
            window.addEventListener('load', syncHeights);
            syncHeights();
        });
    </script>

</body>
</html>