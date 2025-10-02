@extends('layout.header')
@extends('layout.sidebar')


    <div class="main-content-wrapper">
        <div class="table-card">
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Tambah Jadwal</a>
        
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Tanggal</th>  Herwy
                        <th>Waktu</th>
                        <th>Topik</th>
                        <th>Catatan</th> {{-- HEADER DIPERBAIKI --}}
                        <th>Aksi</th>   {{-- HEADER DITAMBAHKAN --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->mahasiswa ?? 'N/A' }}</td>
                        <td>{{ $jadwal->dosen ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                        <td>{{ $jadwal->topik ?? '-' }}</td>
                        <td>{{ $jadwal->catatan ?? '-' }}</td> {{-- DIPINDAHKAN KE KOLOM YANG BENAR --}}
                        
                        {{-- Ini adalah kolom yang benar untuk tombol aksi --}}
                        <td>
                            <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- Pastikan colspan sesuai dengan jumlah kolom --}}
                        <td colspan="7" style="text-align: center;">Belum ada jadwal bimbingan yang ditambahkan.</td>
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
    </div>
