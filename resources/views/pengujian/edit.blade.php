<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pengujian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">
    <h1 class="mb-4">Edit Pengujian</h1>

    <form action="{{ route('pengujian.update', $pengujian->id_pengujian) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_penguji" class="form-label">Dosen Penguji</label>
            <select name="id_penguji" id="id_penguji" class="form-select" required>
                @foreach ($dosen as $d)
                    <option value="{{ $d->id_penguji }}"
                        {{ $pengujian->id_penguji == $d->id_penguji ? 'selected' : '' }}>
                        {{ $d->nama_dosen }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_tempat" class="form-label">Tempat Pengujian</label>
            <select name="id_tempat" id="id_tempat" class="form-select" required>
                @foreach ($tempat as $t)
                    <option value="{{ $t->id_tempat }}" {{ $pengujian->id_tempat == $t->id_tempat ? 'selected' : '' }}>
                        {{ $t->tempat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pengujian</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $pengujian->tanggal }}"
                required>
        </div>

        <div class="mb-3">
            <label for="jam" class="form-label">Jam Pengujian</label>
            <input type="time" name="jam" id="jam" class="form-control" value="{{ $pengujian->jam }}"
                required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pengujian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</body>

</html>
