<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen Penguji</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">

    <body class="container py-4">
        <main class="container py-4">
            <h1 class="mb-4 text-center">Tambah Dosen Penguji</h1>
            {{-- Tampilkan pesan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dosen_penguji.store') }}" method="POST" class="shadow p-4 rounded bg-light">
                @csrf

                <div class="mb-3">
                    <label for="id_dosen" class="form-label fw-bold">Pilih Dosen</label>
                    <select name="id_dosen" id="id_dosen" class="form-select" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosen as $dsn)
                            <option value="{{ $dsn->id_dosen }}"
                                {{ old('id_dosen') == $dsn->id_dosen ? 'selected' : '' }}>
                                {{ $dsn->nama }} - {{ $dsn->nip ?? 'NIP' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_dosen')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label fw-bold">Pilih Mahasiswa</label>
                    <select name="id_mahasiswa" id="id_mahasiswa" class="form-select" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach ($Mahasiswa as $mhs)
                            <option value="{{ $mhs->id_mahasiswa }}"
                                {{ old('id_mahasiswa') == $mhs->id_mahasiswa ? 'selected' : '' }}>
                                {{ $mhs->nama }} - {{ $mhs->nim ?? 'NIM' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mahasiswa')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                {{-- <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}"
                    required>
            </div> --}}

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('dosen_penguji.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </main>
</div>
</body>

</html>
