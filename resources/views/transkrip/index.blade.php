<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transkrip Kelayakan PKL - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link rel="stylesheet" href="/assets/css/transkrip.css">
</head>
<body>

<div class="d-flex">
    @include('layout.header')
</div>

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Daftar Transkrip Kelayakan PKL</h2>
        <div>
            {{-- LOGIKA 1: Tombol 'Analisa Transkrip' --}}
            {{-- Diakses oleh: Koordinator DAN Mahasiswa --}}
            @if(in_array(Auth::user()->role, ['koordinator', 'mahasiswa']))
                <a href="{{ route('transkrip.analyzePdfView') }}" class="btn btn-info" style="margin-right: 10px;">
                    <i class="fas fa-file-import"></i> Analisa Transkrip
                </a>
            @endif

            {{-- LOGIKA 2: Tombol 'Tambah Data' (Manual) --}}
            {{-- Biasanya input manual hanya untuk Koordinator.
                 Mahasiswa disarankan pakai 'Analisa'.
                 Jika mahasiswa juga boleh input manual, tambahkan 'mahasiswa' di array --}}
            @if(Auth::user()->role == 'koordinator')
                <a href="{{ route('transkrip.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- search --}}
    <div class="search-container">
        <form action="{{ route('transkrip.index') }}" method="GET">
            <input type="text" name="search" id="searchInput" class="search-input" placeholder="Cari NIM atau Nama Mahasiswa..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-search" id="searchBtn" title="Cari"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <div class="content-wrapper">
    <div class="result-card">
        @if(count($data) > 0)
            <div class="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>IPK</th>
                            <th>SKS D</th>
                            <th>Ada E</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $row)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="NIM">{{ $row->nim }}</td>
                            <td data-label="Nama">{{ $row->nama_mahasiswa }}</td>
                            <td data-label="IPK">{{ number_format($row->ipk, 2) }}</td>
                            <td data-label="SKS D">{{ $row->total_sks_d }}</td>
                            <td data-label="Ada E">{{ $row->has_e ? 'Ya' : 'Tidak' }}</td>
                            <td data-label="Status">
                                @if($row->eligible)
                                    <span class="status-badge status-layak">
                                        <i class="fas fa-check-circle"></i> Layak
                                    </span>
                                @else
                                    <span class="status-badge status-tidak-layak">
                                        <i class="fas fa-times-circle"></i> Tidak Layak
                                    </span>
                                @endif
                            </td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}</td>
                            <td data-label="Aksi">
                                {{-- Tombol Detail: Semua Role (termasuk Ketua Prodi) Bisa Lihat --}}
                                <a href="{{ route('transkrip.show', $row->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                {{-- LOGIKA 3: Tombol Edit & Hapus --}}
                                {{-- Hanya untuk Koordinator DAN Mahasiswa --}}
                                @if(in_array(Auth::user()->role, ['koordinator', 'mahasiswa']))
                                    <a href="{{ route('transkrip.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('transkrip.destroy', $row->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data transkrip kelayakan PKL.</p>

                {{-- Tombol di Empty State juga harus diproteksi --}}
                @if(in_array(Auth::user()->role, ['koordinator', 'mahasiswa']))
                    <a href="{{ route('transkrip.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

    <!-- Live Search -->
    <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.table-wrapper tbody tr');

        rows.forEach(row => {
            const nim = (row.querySelectorAll('td')[1]?.textContent || '').toLowerCase();
            const nama = (row.querySelectorAll('td')[2]?.textContent || '').toLowerCase();

            const match = nim.includes(filter) || nama.includes(filter);
            row.style.display = match ? '' : 'none';
        });
    });

    // search button click
    document.getElementById('searchBtn').addEventListener('click', function(){
        document.getElementById('searchInput').dispatchEvent(new Event('keyup'));
    });
    </script>

</body>
</html>
