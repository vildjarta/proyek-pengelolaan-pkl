<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal Bimbingan</title>
</head>
<body>

    <h2>✏️ Edit Jadwal Bimbingan</h2>

    @if ($errors->any())
        <div style="color:red;">
            <strong>⚠️ Terjadi kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
        @csrf 
        @method('PUT')

        <label>Nama Mahasiswa:</label>
        <input type="text" name="nama_mahasiswa" value="{{ $jadwal->nama_mahasiswa }}" required><br><br>

        <label>Dosen Pembimbing:</label>
        <input type="text" name="dosen_pembimbing" value="{{ $jadwal->dosen_pembimbing }}" required><br><br>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="{{ $jadwal->tanggal }}" required><br><br>

        <label>Waktu:</label>
        <input type="time" name="waktu" value="{{ $jadwal->waktu }}" required><br><br>

        <label>Topik:</label>
        <input type="text" name="topik" value="{{ $jadwal->topik }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('jadwal.index') }}">⬅️ Kembali ke Daftar Jadwal</a>

</body>
</html>
