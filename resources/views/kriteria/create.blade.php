<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kriteria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f8ff;
        }

        .main-content-wrapper {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 60, 130, 0.1);
            margin: 2rem;
            padding: 2rem;
        }

        h1 {
            color: #0d6efd;
            font-weight: 600;
        }

        .btn {
            border-radius: 8px;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="d-flex">
        @include('layout.header')
    </div>

    {{-- Sidebar --}}
    <div class="d-flex">
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper mb-5">
        <h1 class="mb-4">Tambah Kriteria</h1>

        {{-- Validasi Error Alert --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Periksa kembali input anda:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('kriteria.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kriteria" class="form-label">Kriteria</label>
                <input type="text" name="kriteria" id="kriteria" class="form-control"
                    placeholder="Masukkan nama kriteria" value="{{ old('kriteria') }}" required>
            </div>

            <div class="mb-4">
                <label for="bobot" class="form-label">Bobot</label>
                <input type="number" name="bobot" id="bobot" class="form-control"
                    placeholder="Masukkan nilai bobot" value="{{ old('bobot') }}" min="0" step="0.01"
                    required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save2"></i> Simpan
                </button>
                <a href="{{ route('kriteria.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>
