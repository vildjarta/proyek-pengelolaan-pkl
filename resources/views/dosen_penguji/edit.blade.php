<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen Penguji</title>
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

        <h1 class="mb-4 text-center">Edit Dosen Penguji</h1>

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

        {{-- Pastikan variabel $dosenPenguji tersedia --}}
        <form action="{{ route('dosen_penguji.update', $dosenPenguji->id_penguji) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NIP --}}
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control"
                    value="{{ old('nip', $dosenPenguji->nip) }}" required>
            </div>

            {{-- Nama Dosen --}}
            <div class="mb-3">
                <label for="nama_dosen" class="form-label">Nama Dosen</label>
                <input type="text" name="nama_dosen" id="nama_dosen" class="form-control"
                    value="{{ old('nama_dosen', $dosenPenguji->nama_dosen) }}" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $dosenPenguji->email) }}" required>
            </div>

            {{-- Nomor HP --}}
            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control"
                    value="{{ old('no_hp', $dosenPenguji->no_hp) }}">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('dosen_penguji.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

    </body>

</html>
