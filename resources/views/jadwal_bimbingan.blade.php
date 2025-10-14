@extends('layout.header')
@extends('layout.sidebar')
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl-jadwal.css') }}">
    <div class="main-content-wrapper">
        <div class="table-card">
            <div class="table-header">
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Tambah Jadwal</a>
                <div class="search-container">
                    <form action="{{ route('jadwal.index') }}" method="GET">
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
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Topik</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
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
                        
                    <td class="text-center">
                        <div class="action-buttons">
                            <a href="{{ route('jadwal.edit',$jadwal->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-pen"></i> Edit
                            </a>
                            <form action="{{ route('jadwal.destroy',$jadwal->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada jadwal bimbingan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
