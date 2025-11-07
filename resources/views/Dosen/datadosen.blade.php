<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Layout Global --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    {{-- CSS Halaman Ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datadosen.css') }}">
</head>
<body>

    {{-- HEADER --}}
    @include('layout.header')

    {{-- SIDEBAR --}}
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">
            <div class="content">
                <div class="table-header d-flex justify-content-between align-items-center">
                    <h2 class="title">Daftar Dosen</h2>

                    <div class="d-flex align-items-center gap-2">
                        <!-- Form Pencarian -->
                        <div class="search-container d-flex align-items-center">
                            <input type="text" id="searchInput" class="search-input" placeholder="Cari Dosen...">
                            <button class="btn btn-search ms-2" id="searchBtn"><i class="fa fa-search"></i></button>
                        </div>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('dosen.create') }}" class="btn btn-primary btn-add">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>

                <!-- Pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success small">{{ session('success') }}</div>
                @endif

                <div class="table-card shadow-sm rounded mt-3">
                    <table class="table table-borderless mb-0" id="dosenTable">
                        <thead class="table-head">
                            <tr>
                                <th class="text-center">NIP</th>
                                <!-- Header Nama di tengah -->
                                <th class="text-center nama-head">Nama</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">No HP</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosen as $row)
                                <tr class="table-row">
                                    <td class="text-center small text-muted nip-col">{{ $row->nip }}</td>
                                    <!-- Nama di tengah -->
                                    <td class="text-center fw-semibold nama-col">{{ $row->nama }}</td>
                                    <td class="text-center small text-muted">{{ $row->email ?? '-' }}</td>
                                    <td class="text-center small">{{ $row->nomor_hp ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="action-buttons d-inline-flex gap-2">
                                            <a href="{{ route('dosen.edit', $row->id) }}" class="btn btn-edit-custom btn-sm" title="Edit">
                                                <i class="fa fa-pen"></i>
                                            </a>

                                            <form action="{{ route('dosen.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete-custom btn-sm" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data dosen.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Live Search -->
    <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#dosenTable tbody tr.table-row');

        rows.forEach(row => {
            const nip = (row.querySelectorAll('td')[0]?.textContent || '').toLowerCase();
            const nama = (row.querySelectorAll('td')[1]?.textContent || '').toLowerCase();
            const email = (row.querySelectorAll('td')[2]?.textContent || '').toLowerCase();
            const hp = (row.querySelectorAll('td')[3]?.textContent || '').toLowerCase();

            const match = nip.includes(filter) || nama.includes(filter) || email.includes(filter) || hp.includes(filter);
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
