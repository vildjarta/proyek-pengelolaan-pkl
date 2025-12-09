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
        /* ========== Perbaikan spacing / scroll agar header tidak menutupi konten ganda ==========
           - Tidak mengubah tampilan (warna/ukuran), hanya rapikan jarak
           - Sesuaikan --header-height kalau header mu berbeda
        */
        :root {
            --header-height: 60px;      /* sesuai header CSS kamu */
            --sidebar-width: 285px;     /* sesuai sidebar CSS kamu */
            --sidebar-collapsed: 70px;  /* sesuai behavior CSS */
        }

        /* Aturan utama: header fixed, sehingga jangan beri margin-top ganda pada .main-content */
        body {
            margin: 0;
            /* header partial kemungkinan sudah menambahkan body { padding-top: var(--header-height) }.
               Supaya tidak terjadi gap ganda, kita tidak menambahkan margin-top lagi di sini. */
        }

        /* Konten utama: cukup gap kecil di dalam card, dan gunakan scroll area khusus */
        .main-content {
            /* jangan gunakan margin-top besar — header sudah mengatur ruang melalui padding-top di header partial */
            margin-left: var(--sidebar-width);
            margin-top: 0;
            padding: 20px;
            transition: margin-left 0.3s ease;
            box-sizing: border-box;

            /* pastikan area utama mengisi tinggi layar minus header */
            min-height: calc(100vh - var(--header-height));
            /* kita akan menempatkan overflow pada .main-scroll, bukan di body */
            position: relative;
            background: transparent;
        }

        /* Jika body diberi class sidebar-closed oleh header toggle, geser margin-left */
        body.sidebar-closed .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Ini adalah viewport scroll area untuk konten — header & sidebar tetap visible */
        .main-scroll {
            height: calc(100vh - var(--header-height)); /* total tinggi yang bisa discroll */
            overflow-y: auto;
            padding-bottom: 40px; /* beri ruang di bawah supaya konten terakhir tidak mepet */
        }

        /* sedikit spacing supaya card header tidak terasa terlalu jauh bawah */
        .page-header {
            margin-bottom: 12px;
        }

        /* kosmetik tabel kecil (tetap sama, tidak ubah tampilan) */
        table { font-size: 14px; }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 12px;
            }
            /* ketika di mobile, .main-scroll tetap bekerja */
            .main-scroll {
                height: calc(100vh - var(--header-height));
            }
        }

        /* ===== keep your existing dropdown fix (tidak dihapus) ===== */
        /* Dropdown di sidebar: pakai behaviour sendiri, bukan Bootstrap */
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

    {{-- Header & Sidebar partial (tetap sama seperti sekarang) --}}
    @include('layout.header')
    @include('layout.sidebar')

    {{-- MAIN CONTENT: gunakan .main-scroll untuk scroll area sehingga header/sidebar selalu terlihat --}}
    <div class="main-content">
        <div class="main-scroll">
            <div class="container-fluid">
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
                                        <th>Aksi</th>
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
                </div> <!-- card -->
            </div> <!-- container-fluid -->
        </div> <!-- main-scroll -->
    </div> <!-- main-content -->

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pastikan sidebar toggle tetap berfungsi seperti sebelumnya (menambahkan class di body)
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.menu-toggle');
            const bodyEl = document.body;

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    bodyEl.classList.toggle('sidebar-closed');

                    // simpan preferensi ke localStorage supaya persist di halaman lain
                    try {
                        localStorage.setItem('app_sidebar_closed', bodyEl.classList.contains('sidebar-closed') ? '1' : '0');
                    } catch(e) {}
                });

                // restore dari localStorage (jika ada)
                try {
                    const stored = localStorage.getItem('app_sidebar_closed');
                    if (stored === '1') bodyEl.classList.add('sidebar-closed');
                } catch(e) {}
            }

            // Sync heights untuk .main-scroll agar pas ketika jendela diresize
            function syncHeights() {
                const root = getComputedStyle(document.documentElement);
                const headerHeight = parseInt(root.getPropertyValue('--header-height')) || 60;
                const mainScroll = document.querySelector('.main-scroll');
                if (mainScroll) mainScroll.style.height = (window.innerHeight - headerHeight) + 'px';
                // sidebar height juga disesuaikan supaya tidak meninggalkan gap
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
