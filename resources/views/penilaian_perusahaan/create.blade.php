<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penilaian</title>
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
        <h1 class="mb-4">Tambah Penilaian Perusahaan</h1>

        {{-- Validasi Error --}}
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

        {{-- Form Input --}}
        <form action="{{ route('penilaian_perusahaan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Perusahaan</label>
                <select name="id_perusahaan" class="form-select" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($perusahaans as $perusahaan)
                        <option value="{{ $perusahaan->id_perusahaan }}">
                            {{ $perusahaan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kriteria</label>
                <select name="id_kriteria" class="form-select" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach ($kriterias as $kriteria)
                        <option value="{{ $kriteria->id_kriteria }}">
                            {{ $kriteria->kriteria }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" name="nilai" id="nilai" class="form-control" min="0" max="100"
                    placeholder="Masukkan nilai 0 - 100" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save2"></i> Simpan
                </button>
                <a href="{{ route('penilaian_perusahaan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </form>

    </div>
</body>

</html>
