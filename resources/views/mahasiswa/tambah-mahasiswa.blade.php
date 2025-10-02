@extends('layout.app')

@section('content')
<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="card shadow border-0 rounded-3">
    <div class="card-header bg-primary text-white py-3">
        <h4 class="mb-0 fw-bold">
            <i class="fa fa-plus-circle me-2"></i> Tambah Data Mahasiswa
        </h4>
    </div>

    <div class="card-body p-4">

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-exclamation-circle me-2"></i> Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Form tambah --}}
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- NIM hanya angka --}}
                <div class="col-md-6">
                    <label for="nim" class="form-label fw-bold">NIM</label>
                    <input type="number" name="nim" id="nim"
                           value="{{ old('nim') }}"
                           class="form-control"
                           required min="1000000000" max="999999999999"
                           title="NIM harus terdiri dari 10 digit angka.">
                </div>

                {{-- Nama hanya huruf --}}
                <div class="col-md-6">
                    <label for="nama" class="form-label fw-bold">Nama</label>
                    <input type="text" name="nama" id="nama"
                           value="{{ old('nama') }}"
                           class="form-control"
                           required pattern="[A-Za-z\s]+"
                           title="Nama hanya boleh berisi huruf dan spasi.">
                </div>

                {{-- Email valid --}}
                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="form-control"
                           required
                           title="Gunakan format email yang benar, contoh: nama@email.com">
                </div>

                {{-- No HP hanya angka --}}
                <div class="col-md-6">
                    <label for="no_hp" class="form-label fw-bold">No HP</label>
                    <input type="number" name="no_hp" id="no_hp"
                           value="{{ old('no_hp') }}"
                           class="form-control"
                           required min="1000000000" max="999999999999999"
                           title="Nomor HP harus terdiri dari 10-15 digit angka.">
                </div>

                {{-- Prodi dropdown --}}
                <div class="col-md-4">
                    <label for="prodi" class="form-label fw-bold">Prodi</label>
                    <select name="prodi" id="prodi" class="form-select" required>
                        <option value="">-- Pilih Prodi --</option>
                        <option value="Akuntansi" {{ old('prodi') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="Agroindustri" {{ old('prodi') == 'Agroindustri' ? 'selected' : '' }}>Agroindustri</option>
                        <option value="Teknologi Informasi" {{ old('prodi') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        <option value="Teknologi Otomotif" {{ old('prodi') == 'Teknologi Otomotif' ? 'selected' : '' }}>Teknologi Otomotif</option>
                        <option value="Akuntansi Perpajakan (D4)" {{ old('prodi') == 'Akuntansi Perpajakan (D4)' ? 'selected' : '' }}>Akuntansi Perpajakan (D4)</option>
                        <option value="Teknologi Pakan Ternak (D4)" {{ old('prodi') == 'Teknologi Pakan Ternak (D4)' ? 'selected' : '' }}>Teknologi Pakan Ternak (D4)</option>
                        <option value="Teknologi Rekayasa Komputer Jaringan (D4)" {{ old('prodi') == 'Teknologi Rekayasa Komputer Jaringan (D4)' ? 'selected' : '' }}>Teknologi Rekayasa Komputer Jaringan (D4)</option>
                        <option value="Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)" {{ old('prodi') == 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)' ? 'selected' : '' }}>Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)</option>
                    </select>
                </div>

                {{-- Angkatan hanya angka mulai dari 2009 --}}
                <div class="col-md-4">
                    <label for="angkatan" class="form-label fw-bold">Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan"
                           value="{{ old('angkatan') }}"
                           class="form-control"
                           required min="2009" max="{{ date('Y') + 1 }}">
                </div>

                {{-- IPK desimal 0.00 - 4.00 --}}
                <div class="col-md-4">
                    <label for="ipk" class="form-label fw-bold">IPK</label>
                    <input type="number" step="0.01" min="0" max="4"
                           name="ipk" id="ipk"
                           value="{{ old('ipk') }}"
                           class="form-control"
                           required
                           title="IPK harus berupa angka antara 0.00 sampai 4.00">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="fa fa-arrow-left me-2"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fa fa-save me-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection