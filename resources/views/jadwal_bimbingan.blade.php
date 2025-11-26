@extends('layout.header')
@extends('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl-jadwal.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="main-content-wrapper">
    <div class="table-card">
        <div class="table-header">
            {{-- LOGIKA: Sembunyikan tombol Tambah untuk Mahasiswa --}}
            @if(auth()->user()->role !== 'mahasiswa')
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
            @endif
            
            <div class="search-container">
                <form action="{{ route('jadwal.index') }}" method="GET">
                    <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'tanggal' }}">
                    <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'asc' }}">
                    <input type="text" name="search" class="form-control search-input" placeholder="Cari Mahasiswa/Dosen..." value="{{ $search ?? '' }}">
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Dosen</th>

                    @php
                        $tanggalDirection = ($sortBy == 'tanggal' && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $waktuDirection = ($sortBy == 'waktu_mulai' && $sortDirection == 'asc') ? 'desc' : 'asc';
                    @endphp

                    <th>
                        <a href="{{ route('jadwal.index', ['search' => $search, 'sort_by' => 'tanggal', 'sort_direction' => $tanggalDirection]) }}" class="sort-link {{ $sortBy == 'tanggal' ? 'active '.($sortDirection=='desc' ? 'sort-desc' : 'sort-asc') : '' }}">
                            <span>Tanggal</span>
                            <span class="sort-icon" aria-hidden="true">
                                <i class="fas fa-angle-up up"></i>
                                <i class="fas fa-angle-down down"></i>
                            </span>
                        </a>
                    </th>

                    <th>
                        <a href="{{ route('jadwal.index', ['search' => $search, 'sort_by' => 'waktu_mulai', 'sort_direction' => $waktuDirection]) }}" class="sort-link {{ $sortBy == 'waktu_mulai' ? 'active '.($sortDirection=='desc' ? 'sort-desc' : 'sort-asc') : '' }}">
                            <span>Waktu</span>
                            <span class="sort-icon" aria-hidden="true">
                                <i class="fas fa-angle-up up"></i>
                                <i class="fas fa-angle-down down"></i>
                            </span>
                        </a>
                    </th>

                    <th>Topik</th>
                    <th>Catatan</th>
                    
                    {{-- Kolom Aksi hanya muncul jika BUKAN mahasiswa --}}
                    @if(auth()->user()->role !== 'mahasiswa')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $jadwal->mahasiswa->nama ?? 'N/A' }}</td>
                    <td>{{ $jadwal->dosen->nama ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                    <td>{{ $jadwal->topik ?? '-' }}</td>
                    <td>{{ $jadwal->catatan ?? '-' }}</td>
                    
                    {{-- LOGIKA: Sembunyikan tombol Edit & Hapus untuk Mahasiswa --}}
                    @if(auth()->user()->role !== 'mahasiswa')
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="{{ route('jadwal.edit',$jadwal->id) }}" class="btn btn-edit-custom">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('jadwal.destroy',$jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role !== 'mahasiswa' ? '7' : '6' }}" style="text-align: center;">
                        Belum ada jadwal bimbingan atau data tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script>
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
});
</script>
</div>