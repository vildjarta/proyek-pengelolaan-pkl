<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadwal Bimbingan</title>
</head>
<body>

    <h2>📅 Daftar Jadwal Bimbingan</h2>

    <a href="{{ route('jadwal.create') }}">➕ Tambah Jadwal</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Mahasiswa</th>
            <th>Dosen</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Topik</th>
            <th>Aksi</th>
        </tr>
        @forelse($jadwal as $j)
        <tr>
            <td>{{ $j->id }}</td>
            <td>{{ $j->nama_mahasiswa }}</td>
            <td>{{ $j->dosen_pembimbing }}</td>
            <td>{{ $j->tanggal }}</td>
            <td>{{ $j->waktu }}</td>
            <td>{{ $j->topik }}</td>
            <td>
                <a href="{{ route('jadwal.edit', $j->id) }}">✏️ Edit</a> |
                <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus jadwal ini?')">🗑️ Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7">Belum ada jadwal.</td></tr>
        @endforelse
    </table>

</body>
</html>
