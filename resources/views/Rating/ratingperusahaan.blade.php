<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ranking Perusahaan - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Global Layout --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">

    {{-- CSS Halaman Ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/ratingperusahaan.css') }}">
</head>
<body>
    {{-- HEADER --}}
    @include('layout.header')

    {{-- SIDEBAR --}}
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper">
        <div class="content container-fluid">
            <div class="content">
                <div class="table-header">
                    <h2 class="title">Ranking Perusahaan</h2>

                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('ratingperusahaan') }}" method="GET" class="d-flex search-container">
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" class="search-input" placeholder="Cari perusahaan...">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>

                <table class="table table-striped" id="rankingTable">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Perusahaan</th>
                            <th>Rata-Rata Rating</th>
                            <th>Jumlah Rating</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse($perusahaans as $index => $p)
        @php
            $companyName = $p->nama_perusahaan ?? '-';
            $avg = floatval($p->avg_rating ?? 0);
            $count = intval($p->total_reviews ?? 0);
            $avgStars = (int) round($avg);
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $companyName }}</td>
            <td>
    <div class="rating-wrapper">
        <div class="stars">
            @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $avgStars ? 'filled' : '' }}"></i>
            @endfor
        </div>
        <div class="rating-label">
            Rata-rata rating {{ number_format($avg, 1) }}
        </div>
    </div>
</td>

            <td>
                <span class="badge-rating-count">{{ $count }}</span> orang
            </td>
            <td>
                <div class="action-buttons">
                    <a href="{{ route('lihatratingdanreview', ['id_perusahaan' => $p->id_perusahaan]) }}" class="btn btn-view" title="Lihat Review">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="{{ route('tambahratingdanreview', $p->id_perusahaan) }}" class="btn btn-add" title="Tambah Review">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4">
                @if(request('search'))
                    Tidak ditemukan perusahaan dengan nama "<strong>{{ request('search') }}</strong>"
                @else
                    Belum ada data perusahaan yang dirating.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>

    <script>
        // Filter pencarian dinamis tanpa reload
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('rankingTable');

            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = table.querySelectorAll("tbody tr");
                rows.forEach(row => {
                    const namaCell = row.cells[1];
                    if (!namaCell) return;
                    const nama = (namaCell.textContent || namaCell.innerText).toLowerCase();
                    row.style.display = nama.includes(filter) ? "" : "none";
                });
            });
        });
    </script>
</body>
</html>
