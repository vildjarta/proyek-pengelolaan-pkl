<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengujian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h1 class="mb-4">Tambah Pengujian</h1>

    <form action="{{ route('pengujian.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_penguji" class="form-label">Dosen Penguji</label>
            <select name="id_penguji" id="id_penguji" class="form-select" required>
                <option value="">-- Pilih Dosen Penguji --</option>
                @foreach($dosen as $d)
                    <option value="{{ $d->id_penguji }}">{{ $d->nama_dosen }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_tempat" class="form-label">Tempat Pengujian</label>
            <select name="id_tempat" id="id_tempat" class="form-select" required>
                <option value="">-- Pilih Tempat --</option>
                @foreach($tempat as $t)
                    <option value="{{ $t->id_tempat }}">{{ $t->tempat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pengujian</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="jam" class="form-label">Jam Pengujian</label>
            <input type="time" name="jam" id="jam" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pengujian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>
