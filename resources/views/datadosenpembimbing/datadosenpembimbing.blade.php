<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen Pembimbing - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Layout Global --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    
    {{-- CSS Halaman Ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datadosenpembimbing.css') }}">
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
                <div class="table-header">
                    <h2 class="title">Daftar Dosen Pembimbing</h2>

                    <div class="d-flex align-items-center gap-2">
                        <!-- Form Pencarian -->
                        <form action="{{ route('datadosenpembimbing.index') }}" method="GET" class="d-flex align-items-center">
                            <input type="text" name="search" id="searchInput" class="search-input" placeholder="Cari dosen..." value="{{ request('search') }}">
                            <button class="btn btn-primary ms-2" type="submit"><i class="fa fa-search"></i></button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('datadosenpembimbing.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>

                {{-- flash message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped" id="dosenTable">
                    <thead>
                        <tr>
                            <th class="text-center">NIP</th>
                            <th class="text-start">Nama</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">No. HP</th>
                            <th class="text-start">Mahasiswa Bimbingan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="text-center">{{ $row->NIP }}</td>
                                <td class="text-start">{{ $row->nama }}</td>
                                <td class="text-center">{{ $row->email }}</td>
                                <td class="text-center">{{ $row->no_hp ?? '-' }}</td>
                                <td class="mahasiswa-bimbingan">
                                    @if($row->mahasiswa->count() > 0)
                                        <ul>
                                            @foreach($row->mahasiswa as $mhs)
                                                <li class="mahasiswa-item">
                                                    <span class="nomor">{{ $loop->iteration }}.</span>
                                                    <span class="nama">{{ $mhs->nama }}</span>
                                                    <span class="nim">({{ $mhs->nim }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Belum memiliki mahasiswa</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('datadosenpembimbing.edit', $row->id_pembimbing) }}" class="btn btn-edit-custom" title="Edit">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <form action="{{ route('datadosenpembimbing.destroy', $row->id_pembimbing) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini?')" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data dosen pembimbing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    // === Live Search non-JS fallback: kept but form search will do server-side search. 
    // Jika ingin live-filter client-side, uncomment dan gunakan bagian ini.
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dosenTable tbody tr');

        rows.forEach(row => {
            let namaDosen = row.querySelectorAll('td')[1]?.textContent.toLowerCase() || '';
            row.style.display = namaDosen.includes(filter) ? '' : 'none';
        });
    });
    </script>
</body>
</html>
