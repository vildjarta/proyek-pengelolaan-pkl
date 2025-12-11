<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penilaian Dosen Penguji - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== LAYOUT UTAMA ===== */
        .main-content-wrapper {
            padding: 30px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* ===== CARD STYLING ===== */
        .card {
            box-shadow: var(--shadow-lg);
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            background: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: var(--primary-gradient) !important;
            border: none !important;
            padding: 25px 30px !important;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .card-header h4 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .card-body {
            padding: 30px !important;
            background: #ffffff;
        }

        /* ===== TABLE CONTAINER ===== */
        .table-container {
            width: 100%;
            overflow: visible;
            border-radius: var(--radius-md);
        }

        .compact-table {
            width: 100%;
            font-size: 13px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        /* ===== KOLOM WIDTH ===== */
        .compact-table th,
        .compact-table td {
            padding: 12px 8px;
            vertical-align: middle;
            text-align: center;
            word-wrap: break-word;
            border-bottom: 1px solid #e8edf2;
            border-right: 1px solid #e8edf2;
            transition: var(--transition-smooth);
        }

        .compact-table th:last-child,
        .compact-table td:last-child {
            border-right: none;
        }

        .col-nip { width: 110px; }
        .col-dosen { width: 130px; text-align: left !important; }
        .col-mahasiswa { width: 130px; text-align: left !important; }
        .col-presentasi { width: 85px; }
        .col-materi { width: 95px; }
        .col-hasil { width: 75px; }
        .col-objektif { width: 95px; }
        .col-laporan { width: 85px; }
        .col-total { width: 95px; }
        .col-nilai-akhir { width: 110px; }
        .col-tanggal { width: 110px; }
        .col-aksi { width: 100px; }

        /* ===== TABLE HEADER STYLING ===== */
        .table-header-main {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        .table-header-sub {
            background: linear-gradient(135deg, #2a5298 0%, #3a6ab8 100%);
            color: white;
            font-weight: 600;
        }

        .compact-header {
            font-size: 11px;
            line-height: 1.4;
            padding: 10px 6px;
        }

        .compact-header .main-title {
            font-weight: 700;
            margin-bottom: 3px;
            display: block;
            font-size: 12px;
        }

        .compact-header .sub-title {
            font-size: 10px;
            opacity: 0.95;
            display: block;
            font-style: italic;
        }

        /* ===== BUTTON STYLING ===== */
        .btn-light {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid rgba(255,255,255,0.3);
            color: #667eea;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 25px;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .btn-light:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.35);
            color: #764ba2;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
            color: white;
            transition: var(--transition-smooth);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #fda085 0%, #f6d365 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(253, 160, 133, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            border: none;
            color: white;
            transition: var(--transition-smooth);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f45c43 0%, #eb3349 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(235, 51, 73, 0.4);
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            margin: 2px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* ===== ALERT STYLING ===== */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 18px 24px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            animation: slideInDown 0.5s ease;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: var(--success-gradient);
            color: white;
            font-weight: 600;
        }

        /* ===== NO DATA STYLING ===== */
        .no-data {
            padding: 60px 20px;
            text-align: center;
            color: #8b95a5;
            font-style: italic;
            font-size: 15px;
        }

        .no-data i {
            font-size: 48px;
            display: block;
            margin-bottom: 15px;
            color: #cbd5e0;
        }

        /* ===== TABLE ROW HOVER ===== */
        .table-hover tbody tr {
            transition: var(--transition-smooth);
            cursor: pointer;
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* ===== NILAI CELL STYLING ===== */
        .nilai-cell {
            font-weight: 700;
            font-size: 14px;
            color: #2d3748;
        }

        .total-nilai {
            background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%) !important;
            font-weight: 800;
            font-size: 15px;
            color: #22543d;
            border-radius: 8px;
            padding: 8px !important;
        }

        .nilai-akhir {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
            font-weight: 800;
            font-size: 15px;
            color: #2c5282;
            border-radius: 8px;
            padding: 8px !important;
        }

        /* ===== RESPONSIVE UNTUK SIDEBAR ===== */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* ===== RESPONSIVE UNTUK LAYAR KECIL ===== */
        @media (max-width: 1200px) {
            .compact-table {
                font-size: 12px;
            }
            
            .compact-table th,
            .compact-table td {
                padding: 10px 6px;
            }
            
            .col-nip { width: 100px; }
            .col-dosen { width: 120px; }
            .col-mahasiswa { width: 120px; }
            .col-presentasi { width: 80px; }
            .col-materi { width: 90px; }
            .col-hasil { width: 70px; }
            .col-objektif { width: 90px; }
            .col-laporan { width: 80px; }
            .col-total { width: 90px; }
            .col-nilai-akhir { width: 100px; }
            .col-tanggal { width: 100px; }
            .col-aksi { width: 90px; }
        }

        /* ===== MOBILE CARD VIEW ===== */
        .mobile-card-view {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-table-view {
                display: none;
            }
            
            .mobile-card-view {
                display: block;
            }
            
            .nilai-card {
                background: white;
                border-radius: var(--radius-md);
                padding: 20px;
                margin-bottom: 15px;
                box-shadow: var(--shadow-md);
                border-left: 5px solid;
                border-image: var(--primary-gradient) 1;
                animation: fadeInUp 0.5s ease;
                transition: var(--transition-smooth);
            }

            .nilai-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-lg);
            }
            
            .nilai-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 2px solid #e8edf2;
            }

            .nilai-card-header strong {
                font-size: 16px;
                color: #2d3748;
            }

            .nilai-card-header .text-muted {
                font-size: 13px;
                color: #718096;
            }
            
            .nilai-card-body {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            
            .nilai-item {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                font-size: 13px;
                color: #4a5568;
            }

            .nilai-item span:first-child {
                font-weight: 600;
            }

            .nilai-item strong {
                color: #2d3748;
            }
            
            .nilai-item.total {
                grid-column: 1 / -1;
                background: var(--success-gradient);
                padding: 12px;
                border-radius: var(--radius-sm);
                font-weight: 700;
                margin-top: 8px;
                color: white;
            }
            
            .nilai-item.akhir {
                grid-column: 1 / -1;
                background: var(--info-gradient);
                padding: 12px;
                border-radius: var(--radius-sm);
                font-weight: 700;
                color: white;
            }
        }

        /* ===== LAYAR SANGAT KECIL ===== */
        @media (max-width: 576px) {
            .main-content-wrapper {
                padding: 10px;
            }
            
            .card-header {
                padding: 20px 15px !important;
            }
            
            .card-header h4 {
                font-size: 1.1rem;
            }

            .card-body {
                padding: 15px !important;
            }
        }

        /* ===== HEADER ROWSPAN ===== */
        .header-rowspan {
            vertical-align: middle !important;
        }

        /* ===== BORDER TABLE ===== */
        .compact-table {
            border: 1px solid #e8edf2;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body>

{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== WRAPPER UTAMA ====== --}}
<div class="main-content-wrapper">
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-clipboard me-2"></i> Daftar Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian-penguji.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
                    <i class="fa fa-plus me-1"></i> Tambah Penilaian
                </a>
            </div>

            <div class="card-body">
                {{-- Pesan sukses --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                {{-- TABEL PENILAIAN (DESKTOP) --}}
                <div class="desktop-table-view">
                    <div class="table-container">
                        <table class="table table-bordered table-hover align-middle compact-table">
                            <thead>
                                <tr class="table-header-main">
                                    <th class="col-nip header-rowspan" rowspan="2">NIP</th>
                                    <th class="col-dosen header-rowspan" rowspan="2">Nama Dosen</th>
                                    <th class="col-mahasiswa header-rowspan" rowspan="2">Nama Mahasiswa</th>
                                    <th colspan="5" style="background-color: #2c3e50; color: white;">Komponen Penilaian</th>
                                    <th class="col-total header-rowspan" rowspan="2">Total Nilai</th>
                                    <th class="col-nilai-akhir header-rowspan" rowspan="2">Nilai Akhir (20%)</th>
                                    <th class="col-tanggal header-rowspan" rowspan="2">Tanggal</th>
                                    <th class="col-aksi header-rowspan" rowspan="2">Aksi</th>
                                </tr>
                                <tr class="table-header-sub">
                                    <th class="col-presentasi compact-header">
                                        <span class="main-title">Presentasi</span>
                                        <span class="sub-title">(10%)</span>
                                    </th>
                                    <th class="col-materi compact-header">
                                        <span class="main-title">Pemahaman</span>
                                        <span class="sub-title">(15%)</span>
                                    </th>
                                    <th class="col-hasil compact-header">
                                        <span class="main-title">Hasil</span>
                                        <span class="sub-title">(40%)</span>
                                    </th>
                                    <th class="col-objektif compact-header">
                                        <span class="main-title">Objektivitas</span>
                                        <span class="sub-title">(20%)</span>
                                    </th>
                                    <th class="col-laporan compact-header">
                                        <span class="main-title">Laporan</span>
                                        <span class="sub-title">(15%)</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penilaian as $p)
                                <tr>
                                    <td class="col-nip">{{ $p->dosen->nip ?? '-' }}</td>
                                    <td class="col-dosen">{{ $p->dosen->nama_dosen ?? '-' }}</td>
                                    <td class="col-mahasiswa">{{ $p->nama_mahasiswa }}</td>
                                    <td class="col-presentasi nilai-cell">{{ $p->presentasi }}</td>
                                    <td class="col-materi nilai-cell">{{ $p->materi }}</td>
                                    <td class="col-hasil nilai-cell">{{ $p->hasil }}</td>
                                    <td class="col-objektif nilai-cell">{{ $p->objektif }}</td>
                                    <td class="col-laporan nilai-cell">{{ $p->laporan }}</td>
                                    <td class="col-total total-nilai">{{ $p->total_nilai }}</td>
                                    <td class="col-nilai-akhir nilai-akhir">{{ $p->nilai_akhir }}</td>
                                    <td class="col-tanggal">{{ $p->tanggal_ujian }}</td>
                                    <td class="col-aksi">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('penilaian-penguji.edit', $p->id) }}" class="btn btn-warning btn-sm" title="Edit Penilaian">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('penilaian-penguji.destroy', $p->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('⚠️ Yakin ingin menghapus data penilaian ini?\n\nData yang dihapus tidak dapat dikembalikan!')" title="Hapus Penilaian">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        <div>Belum ada data penilaian</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD VIEW (MOBILE) --}}
                <div class="mobile-card-view">
                    @forelse ($penilaian as $p)
                    <div class="nilai-card">
                        <div class="nilai-card-header">
                            <div>
                                <strong>{{ $p->nama_mahasiswa }}</strong>
                                <div class="text-muted small">{{ $p->dosen->nama_dosen ?? '-' }}</div>
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('penilaian-penguji.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('penilaian-penguji.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus data ini?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="nilai-card-body">
                            <div class="nilai-item">
                                <span>NIP:</span>
                                <strong>{{ $p->dosen->nip ?? '-' }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Presentasi:</span>
                                <strong>{{ $p->presentasi }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Pemahaman:</span>
                                <strong>{{ $p->materi }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Hasil:</span>
                                <strong>{{ $p->hasil }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Objektivitas:</span>
                                <strong>{{ $p->objektif }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Laporan:</span>
                                <strong>{{ $p->laporan }}</strong>
                            </div>
                            <div class="nilai-item total">
                                <span>Total Nilai:</span>
                                <strong>{{ $p->total_nilai }}</strong>
                            </div>
                            <div class="nilai-item akhir">
                                <span>Nilai Akhir (20%):</span>
                                <strong>{{ $p->nilai_akhir }}</strong>
                            </div>
                            <div class="nilai-item">
                                <span>Tanggal:</span>
                                <strong>{{ $p->tanggal_ujian }}</strong>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <div>Belum ada data penilaian</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ====== JAVASCRIPT ====== --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSidebarToggle() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');

        // 🔹 Toggle sidebar
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('sidebar-closed');
            });
        } else {
            setTimeout(setupSidebarToggle, 1000);
        }

        // 🔹 Dropdown profil user
        if (userinfo && profileWrapper) {
            userinfo.addEventListener('click', function(e) {
                e.preventDefault();
                profileWrapper.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if (!profileWrapper.contains(e.target) && profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
    }

    setupSidebarToggle();
});
</script>
</body>
</html>
