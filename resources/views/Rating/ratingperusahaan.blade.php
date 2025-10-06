<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ranking Perusahaan - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

    <style>
        .page-title {
            color: var(--color-text-dark);
            font-weight: 600;
            margin-bottom: 20px;
        }
        .ranking-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px var(--color-shadow);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #e0e6ef;
        }
        th {
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        tr:hover {
            background: #f0f4f8;
        }
        .stars {
            color: #ffc107;
        }
        .stars .fa-star:not(.filled) {
            color: #e0e0e0;
        }
        .rating-text {
            margin-left: 8px;
            color: #555;
            font-size: 0.9em;
            font-weight: 500;
        }
        .action-btn {
            background: #3b5998;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.9em;
            min-width: 150px;
        }
        .action-btn:hover {
            background: #2e4a86;
        }
        .btn-view {
            background: #28a745;
        }
        .btn-view:hover {
            background: #1f8b36;
        }
        .btn-add {
            background: #ffc107;
            color: #000;
        }
        .btn-add:hover {
            background: #e0a800;
            color: #000;
        }
        .search-box {
            margin-bottom: 15px;
        }
        .search-box input {
            padding: 10px;
            width: 100%;
            max-width: 350px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        th:first-child, td:first-child {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            width: 100px;
        }
        th:last-child, td:last-child {
            text-align: center;
            vertical-align: middle;
        }
        td:last-child {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    @include('layouts.header')

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    <div class="main-content-wrapper">
        <div class="content">
            <h2 class="page-title">Ranking Perusahaan</h2>

            <!-- 🔎 Input Pencarian -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari perusahaan...">
            </div>

            <!-- 📊 Tabel Ranking -->
            <div class="ranking-container">
                <table id="rankingTable" class="table">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Perusahaan</th>
                            <th>Rata-rata Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perusahaans as $index => $perusahaan)
                            @php
                                $companyName = $perusahaan->nama_perusahaan ?? $perusahaan->nama ?? '-';
                                $avg = isset($perusahaan->avg_rating) ? floatval($perusahaan->avg_rating) : 0;
                                $avgStars = (int) round($avg);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $companyName }}</td>
                                <td>
                                    <span class="stars" aria-hidden="true">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $avgStars ? 'filled' : '' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="rating-text">Rata-rata: {{ number_format($avg, 1) }}</span>
                                </td>
                                <td>
                                    <!-- Lihat rating & review perusahaan ini -->
                                    <a href="{{ route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan]) }}">
                                        <button class="action-btn btn-view">
                                            <i class="fas fa-eye"></i> Lihat Review
                                        </button>
                                    </a>

                                    <!-- Tambah rating & review perusahaan ini -->
                                    <a href="{{ route('tambahratingdanreview', $perusahaan->id_perusahaan) }}">
                                        <button class="action-btn btn-add">
                                            <i class="fas fa-plus"></i> Tambah Review
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center">Belum ada data perusahaan yang dirating</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🔎 Filter pencarian perusahaan
            const searchEl = document.getElementById('searchInput');
            const table = document.getElementById('rankingTable');
            if (searchEl && table) {
                searchEl.addEventListener('keyup', function () {
                    const filter = this.value.toLowerCase();
                    const rows = table.querySelectorAll("tbody tr");
                    rows.forEach(row => {
                        const cell = row.cells[1];
                        if (!cell) return;
                        const namaPerusahaan = (cell.textContent || cell.innerText).toLowerCase();
                        row.style.display = namaPerusahaan.includes(filter) ? "" : "none";
                    });
                });
            }
        });
    </script>
</body>
</html>
