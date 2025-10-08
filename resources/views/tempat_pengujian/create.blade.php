<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tempat Pengujian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <h1 class="mb-4 text-center">Tambah Tempat Pengujian</h1>

    {{-- Form Tambah Tempat --}}
    <div class="card shadow-sm p-4">
        <form action="{{ route('tempat_pengujian.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tempat" class="form-label">Nama Tempat</label>
                <input type="text" name="tempat" id="tempat"
                    class="form-control @error('tempat') is-invalid @enderror"
                    placeholder="Masukkan nama tempat pengujian" value="{{ old('tempat') }}">

                @error('tempat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('tempat_pengujian.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>

</body>
</html>
