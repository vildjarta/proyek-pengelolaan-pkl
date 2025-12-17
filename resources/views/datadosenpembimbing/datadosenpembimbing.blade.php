<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen Pembimbing - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datadosenpembimbing.css') }}">
</head>
<body>

    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">
            <div class="content">
                <div class="table-header">
                    <h2 class="title">Daftar Dosen Pembimbing</h2>

                    <div class="d-flex align-items-center gap-2">

                        {{-- LOGIKA 1: SEARCH --}}
{{-- SEMUA ROLE (koordinator, staff, dosen_pembimbing, mahasiswa) bisa mencari --}}
@if(in_array(Auth::user()->role, ['koordinator', 'staff', 'dosen_pembimbing', 'mahasiswa']))
    <form action="{{ route('datadosenpembimbing.index') }}" method="GET" class="d-flex align-items-center">
        <input 
            type="text" 
            name="search" 
            id="searchInput" 
            class="search-input" 
            placeholder="Cari dosen..." 
            value="{{ request('search') }}"
        >
        <button class="btn btn-primary ms-2" type="submit">
            <i class="fa fa-search"></i>
        </button>
    </form>
@endif


                        {{-- LOGIKA 2: TOMBOL TAMBAH --}}
                        {{-- Hanya Koordinator yang bisa menambah dosen --}}
                        @if(Auth::user()->role == 'koordinator')
                            <a href="{{ route('datadosenpembimbing.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>

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

                            {{-- LOGIKA 3: KOLOM AKSI --}}
                            {{-- Hanya muncul jika user adalah Koordinator ATAU Dosen Pembimbing --}}
                            {{-- Staff dan Mahasiswa tidak melihat kolom ini --}}
                            @if(in_array(Auth::user()->role, ['koordinator', 'dosen_pembimbing']))
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hideActionsForStaff = auth()->check() && isset(auth()->user()->role) && auth()->user()->role === 'staff';
                        @endphp
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

                                {{-- LOGIKA 4: ISI KOLOM AKSI --}}
                            @if(in_array(Auth::user()->role, ['koordinator', 'dosen_pembimbing']))
                            <td class="text-center">
                                <div class="action-buttons">
                                    
                                    @php
                                        // Cek apakah user adalah pemilik data ini
                                        $isOwnData = (Auth::user()->role == 'dosen_pembimbing' && $row->id_user == Auth::id());
                                        // Cek apakah user adalah koordinator (bisa edit semua)
                                        $isKoordinator = (Auth::user()->role == 'koordinator');
                                    @endphp

                                    {{-- TOMBOL EDIT: Hanya muncul jika Koordinator ATAU Pemilik Data --}}
                                    @if($isKoordinator || $isOwnData)
                                        <a href="{{ route('datadosenpembimbing.edit', $row->id_pembimbing) }}" class="btn btn-edit-custom" title="Edit">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @else
                                        {{-- Opsi: Tampilkan tombol disabled atau kosong jika tidak punya hak --}}
                                        <button class="btn btn-secondary" disabled title="Tidak ada akses">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    @endif

                                    {{-- TOMBOL HAPUS: HANYA Koordinator (Logika sudah benar di file asli) --}}
                                    @if(Auth::user()->role == 'koordinator')
                                        <form action="{{ route('datadosenpembimbing.destroy', $row->id_pembimbing) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        @endif
                            </tr>
                        @empty
                            <tr>
                                {{-- LOGIKA 5: COLSPAN DINAMIS --}}
                                {{-- Jika kolom aksi muncul (koor/dosen) colspan 6, jika tidak (staff/mhs) colspan 5 --}}
                                <td colspan="{{ in_array(Auth::user()->role, ['koordinator', 'dosen_pembimbing']) ? '6' : '5' }}" class="text-center text-muted py-4">
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
// Script pencarian Client-side hanya aktif jika elemen searchInput ada
// (Karena search input disembunyikan untuk mahasiswa)
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dosenTable tbody tr');

        rows.forEach(row => {
            // Pastikan row memiliki cell sebelum mengaksesnya
            let cells = row.querySelectorAll('td');
            if(cells.length > 1) {
                let namaDosen = cells[1].textContent.toLowerCase() || '';
                row.style.display = namaDosen.includes(filter) ? '' : 'none';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');

        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                body.classList.toggle('sidebar-closed');
            });
        }

        if (userinfo) {
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
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
