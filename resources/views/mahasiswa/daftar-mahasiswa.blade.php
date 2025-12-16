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
        :root {
            --header-height: 60px;
            --sidebar-width: 285px;
            --sidebar-collapsed: 70px;
            --primary-custom: #261FB3;
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

        /* Table Styling */
        table { font-size: 14px; }

        thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

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

        /* --- CUSTOM COLORS --- */
        .bg-custom-blue {
            background-color: var(--primary-custom) !important;
        }
        .text-custom-blue {
            color: var(--primary-custom) !important;
        }

        /* --- BUTTON STYLES --- */
        .btn-action {
            width: 32px; /* Diperkecil sedikit agar lebih kompak */
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .btn-edit-custom {
            color: var(--primary-custom);
            border-color: var(--primary-custom);
            background-color: rgba(38, 31, 179, 0.05);
        }
        .btn-edit-custom:hover {
            background-color: var(--primary-custom);
            color: white;
            box-shadow: 0 4px 6px rgba(38, 31, 179, 0.2);
            transform: translateY(-1px);
        }

        .btn-delete-custom {
            color: #dc3545;
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }
        .btn-delete-custom:hover {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);
            transform: translateY(-1px);
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

                    <div class="card-header bg-custom-blue text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 fw-bold fs-5">
                            <i class="fa fa-users me-2"></i> Daftar Mahasiswa
                        </h4>

                        @if(Auth::user()->role == 'koordinator')
                            <a href="{{ route('mahasiswa.create') }}" class="btn btn-light btn-sm text-custom-blue fw-bold shadow-sm">
                                <i class="fa fa-plus me-1"></i> Tambah Mahasiswa
                            </a>
                        @endif
                    </div>

                    {{-- UPDATE: Padding dikurangi dari p-4 menjadi p-2 agar jarak tidak terlalu jauh --}}
                    <div class="card-body p-2">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm m-2" role="alert">
                                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            {{-- UPDATE: border-spacing dikurangi menjadi 0 2px agar baris lebih rapat --}}
                            <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0 2px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 rounded-start ps-3">NIM</th>
                                        <th class="border-0">Nama</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0 text-center">No HP</th>
                                        <th class="border-0">Prodi</th>
                                        <th class="border-0 text-center">Angkatan</th>
                                        <th class="border-0 text-center">IPK</th>
                                        <th class="border-0">Perusahaan</th>

                                        @if(Auth::user()->role == 'koordinator')
                                            <th class="border-0 rounded-end text-center" width="120px">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswa as $m)
                                    <tr class="shadow-sm bg-white rounded">
                                        {{-- Font Regular (tidak tebal) --}}
                                        <td class="border-0 rounded-start ps-3 text-secondary">{{ $m->nim }}</td>
                                        <td class="border-0 text-dark">{{ $m->nama }}</td>
                                        <td class="border-0 text-muted">{{ $m->email }}</td>
                                        <td class="border-0 text-center">{{ $m->no_hp ?? '-' }}</td>
                                        <td class="border-0"><span class="badge bg-light text-dark border fw-normal">{{ $m->prodi }}</span></td>
                                        <td class="border-0 text-center">{{ $m->angkatan }}</td>
                                        <td class="border-0 text-center">{{ optional($m->transcript)->ipk ?? $m->ipk ?? '-' }}</td>
                                        <td class="border-0">{{ $m->perusahaan ?? '-' }}</td>

                                        @if(Auth::user()->role == 'koordinator')
                                            <td class="border-0 rounded-end text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('mahasiswa.edit', $m->id_mahasiswa) }}"
                                                       class="btn-action btn-edit-custom"
                                                       data-bs-toggle="tooltip"
                                                       title="Edit Data">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>

                                                    <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn-action btn-delete-custom"
                                                                data-bs-toggle="tooltip"
                                                                title="Hapus Data"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ $m->nama }}?')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role == 'koordinator' ? 9 : 8 }}" class="text-center py-4 text-muted bg-light rounded">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fa fa-folder-open fa-2x mb-2 text-secondary opacity-50"></i>
                                                <h6 class="fw-normal">Belum ada data mahasiswa</h6>
                                            </div>
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

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

</body>
</html>
