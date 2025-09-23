@extends('home')

@section('content')
    <div class="content">
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
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Topik</th>
<<<<<<< HEAD
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
=======
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->mahasiswa->nama }}</td>
                        <td>{{ $jadwal->dosen->nama }}</td>
                        <td>{{ $jadwal->tanggal }}</td>
                        <td>{{ $jadwal->waktu }}</td>
                        <td>{{ $jadwal->topik }}</td>
                        <td>
                            <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
                </tbody>
            </table>
        </div>
    </div>
@endsection