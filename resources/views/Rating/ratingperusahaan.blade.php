<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ranking Perusahaan - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/ratingperusahaan.css') }}">
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
                                    <span class="stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $avgStars ? 'filled' : '' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="rating-text">Rata-rata: {{ number_format($avg, 1) }}</span>
                                </td>
                                <td>
    <div class="action-group">
        <a href="{{ route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan]) }}">
            <button class="action-btn btn-view">
                <i class="fas fa-eye"></i> Lihat Review
            </button>
        </a>

        <a href="{{ route('tambahratingdanreview', $perusahaan->id_perusahaan) }}">
            <button class="action-btn btn-add">
                <i class="fas fa-plus"></i> Tambah Review
            </button>
        </a>
    </div>
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
