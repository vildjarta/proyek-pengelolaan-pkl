<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Perusahaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <h1 class="mb-4">Edit Perusahaan</h1>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('perusahaan.update', $perusahaan->id_perusahaan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama" id="nama" class="form-control"
                value="{{ old('nama', $perusahaan->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="bidang_usaha" class="form-label">Bidang Usaha</label>
            <input type="text" name="bidang_usaha" id="bidang_usaha" class="form-control"
                value="{{ old('bidang_usaha', $perusahaan->bidang_usaha) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Aktif" {{ old('status', $perusahaan->status) == 'Aktif' ? 'selected' : '' }}>Aktif
                </option>
                <option value="Non-Aktif" {{ old('status', $perusahaan->status) == 'Non-Aktif' ? 'selected' : '' }}>
                    Non-Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="lat" class="form-label">Latitude</label>
            <input type="text" name="lat" id="lat" class="form-control"
                value="{{ old('lat', $perusahaan->lat) }}">
        </div>

        <div class="mb-3">
            <label for="lng" class="form-label">Longitude</label>
            <input type="text" name="lng" id="lng" class="form-control"
                value="{{ old('lng', $perusahaan->lng) }}">
        </div>


        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
    </form>

</body>

</html>
