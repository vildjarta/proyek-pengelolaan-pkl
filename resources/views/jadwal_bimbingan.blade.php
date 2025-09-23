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
                </tbody>
            </table>
        </div>
    </div>
@endsection