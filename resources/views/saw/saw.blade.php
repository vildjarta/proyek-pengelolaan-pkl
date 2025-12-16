<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perhitungan SAW - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome & Bootstrap --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Hanya styling area konten agar tidak menimpa sidebar/header bawaan */
        .main-content {
            margin-left: 250px; /* menyesuaikan lebar sidebar kamu */
            margin-top: 80px;   /* menyesuaikan tinggi header kamu */
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .sidebar-closed .main-content {
            margin-left: 80px;
        }

        table {
            font-size: 14px;
        }

        .card {
            margin-bottom: 20px;
        }

        .ranking-1 {
            background-color: #ffd700 !important;
            font-weight: bold;
        }
        .ranking-2 {
            background-color: #c0c0c0 !important;
        }
        .ranking-3 {
            background-color: #cd7f32 !important;
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

    {{-- Tarik header & sidebar dari file layout --}}
    @include('layout.header')
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">
        {{-- TABEL 1: Data Perusahaan (Nilai Asli) --}}
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <i class="fa fa-table me-2"></i>Tabel 1: Data Kriteria Perusahaan (Nilai Asli)
            </div>
            <div class="card-body p-3">
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Perusahaan</th>
                            <th>Jumlah Mahasiswa</th>
                            <th>Fasilitas</th>
                            <th>Hari Operasi</th>
                            <th>Level Legalitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perusahaan as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>                            
                            <td class="text-start">{{ $m->nama }}</td>
                            <td>{{ $m->jumlah_mahasiswa }}</td>
                            <td>{{ $m->fasilitas }}</td>
                            <td>{{ $m->hari_operasi }}</td>
                            <td>{{ $m->level_legalitas }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="2"><strong>Nilai Maksimum (Xmax)</strong></td>
                            <td><strong>{{ $maxJumlahMahasiswa }}</strong></td>
                            <td><strong>{{ $maxFasilitas }}</strong></td>
                            <td><strong>{{ $maxHariOperasi }}</strong></td>
                            <td><strong>{{ $maxLevelLegalitas }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TABEL 2: Normalisasi Kriteria (r = X / Xmax) --}}
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-success text-white">
                <i class="fa fa-calculator me-2"></i>Tabel 2: Normalisasi Kriteria (r = X / Xmax)
            </div>
            <div class="card-body p-3">
                <div class="alert alert-info">
                    <strong>Rumus Normalisasi:</strong> r = X / Xmax (untuk kriteria benefit/keuntungan)
                </div>
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Perusahaan</th>
                            <th>Jumlah Mahasiswa</th>
                            <th>Fasilitas</th>
                            <th>Hari Operasi</th>
                            <th>Level Legalitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($normalisasi as $id => $n)
                        <tr>
                            <td>{{ $no++ }}</td>                            
                            <td class="text-start">{{ $n['nama'] }}</td>
                            <td>{{ number_format($n['jumlah_mahasiswa'], 4) }}</td>
                            <td>{{ number_format($n['fasilitas'], 4) }}</td>
                            <td>{{ number_format($n['hari_operasi'], 4) }}</td>
                            <td>{{ number_format($n['level_legalitas'], 4) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABEL 3: Bobot Kriteria --}}
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-warning text-dark">
                <i class="fa fa-balance-scale me-2"></i>Tabel 3: Bobot Kriteria (dari AHP)
            </div>
            <div class="card-body p-3">
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jumlah Mahasiswa</td>
                            <td>{{ number_format($bobotKriteria['jumlah_mahasiswa'], 4) }}</td>
                        </tr>
                        <tr>
                            <td>Fasilitas</td>
                            <td>{{ number_format($bobotKriteria['fasilitas'], 4) }}</td>
                        </tr>
                        <tr>
                            <td>Hari Operasi</td>
                            <td>{{ number_format($bobotKriteria['hari_operasi'], 4) }}</td>
                        </tr>
                        <tr>
                            <td>Level Legalitas</td>
                            <td>{{ number_format($bobotKriteria['level_legalitas'], 4) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ number_format($bobotKriteria['jumlah_mahasiswa'] + $bobotKriteria['fasilitas'] + $bobotKriteria['hari_operasi'] + $bobotKriteria['level_legalitas'], 4) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TABEL 4: Perhitungan Nilai SAW --}}
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-info text-white">
                <i class="fa fa-cogs me-2"></i>Tabel 4: Perhitungan Nilai SAW (Normalisasi x Bobot)
            </div>
            <div class="card-body p-3">
                <div class="alert alert-info">
                    <strong>Rumus:</strong> Nilai = (r1 x W1) + (r2 x W2) + (r3 x W3) + (r4 x W4)
                    <br>
                    <small>Dimana r = nilai normalisasi, W = bobot kriteria</small>
                </div>
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Perusahaan</th>
                            <th>Nilai SAW</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($hasilSAW as $id => $h)
                        <tr>
                            <td>{{ $no++ }}</td>                            
                            <td class="text-start">{{ $h['nama'] }}</td>
                            <td>{{ number_format($h['nilai'], 4) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABEL 5: Ranking Perusahaan --}}
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-danger text-white">
                <i class="fa fa-trophy me-2"></i>Tabel 5: Perangkingan Perusahaan
            </div>
            <div class="card-body p-3">
                <div class="alert alert-success">
                    <strong>Hasil Akhir:</strong> Perusahaan diurutkan berdasarkan nilai SAW tertinggi ke terendah.
                </div>
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Ranking</th>
                            <th>Perusahaan</th>
                            <th>Nilai SAW</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ranking as $r)
                        <tr class="{{ $r['ranking'] == 1 ? 'ranking-1' : ($r['ranking'] == 2 ? 'ranking-2' : ($r['ranking'] == 3 ? 'ranking-3' : '')) }}">
                            <td>
                                @if($r['ranking'] == 1)
                                    <i class="fa fa-trophy text-warning"></i>
                                @elseif($r['ranking'] == 2)
                                    <i class="fa fa-medal text-secondary"></i>
                                @elseif($r['ranking'] == 3)
                                    <i class="fa fa-award" style="color: #cd7f32;"></i>
                                @endif
                                {{ $r['ranking'] }}
                            </td>                            
                            <td class="text-start">{{ $r['nama'] }}</td>
                            <td>{{ number_format($r['nilai'], 4) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
