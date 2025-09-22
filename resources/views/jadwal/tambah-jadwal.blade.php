<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Bimbingan</title>
</head>
<body>

    <h2>➕ Tambah Jadwal Bimbingan</h2>

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

    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        <label>Nama Mahasiswa:</label>
        <input type="text" name="nama_mahasiswa" required><br><br>

        <label>Dosen Pembimbing:</label>
        <input type="text" name="dosen_pembimbing" required><br><br>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" required><br><br>

        <label>Waktu:</label>
        <input type="time" name="waktu" required><br><br>

        <label>Topik:</label>
        <input type="text" name="topik"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('jadwal.index') }}">⬅️ Kembali ke Daftar Jadwal</a>

</body>
</html>
