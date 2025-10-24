{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== CSS TAMBAHAN ====== --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

{{-- ====== KONTEN UTAMA ====== --}}
<div class="main-content-wrapper" style="margin-left: 260px; padding: 30px; min-height: 100vh; background-color: #f8f9fa;">
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Data Mahasiswa
                </h4>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-light text-primary fw-bold">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
    {{-- Form Tambah Mahasiswa --}}
    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-6 col-md-12">
                <label for="nim" class="form-label fw-bold">NIM</label>
                <input type="number" name="nim" id="nim"
                       class="form-control" required placeholder="Masukkan NIM"
                       value="{{ old('nim') }}">
            </div>

            <div class="col-lg-6 col-md-12">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <input type="text" name="nama" id="nama"
                       class="form-control" required placeholder="Masukkan Nama"
                       value="{{ old('nama') }}">
            </div>

            <div class="col-lg-6 col-md-12">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" name="email" id="email"
                       class="form-control" required placeholder="Masukkan Email"
                       value="{{ old('email') }}">
            </div>

            <div class="col-lg-6 col-md-12">
                <label for="no_hp" class="form-label fw-bold">No HP</label>
                <input type="number" name="no_hp" id="no_hp"
                       class="form-control" required placeholder="Masukkan Nomor HP"
                       value="{{ old('no_hp') }}">
            </div>

            <div class="col-lg-4 col-md-12">
                <label for="prodi" class="form-label fw-bold">Prodi</label>
                <select name="prodi" id="prodi" class="form-select" required>
                    <option value="">-- Pilih Prodi --</option>
                    <option value="Akuntansi">Akuntansi</option>
                    <option value="Agroindustri">Agroindustri, tambahan</option>
                    <option value="Teknologi Informasi">Teknologi Informasi</option>
                    <option value="Teknologi Otomotif">Teknologi Otomotif</option>
                    <option value="Akuntansi Perpajakan (D4)">Akuntansi Perpajakan (D4)</option>
                    <option value="Teknologi Pakan Ternak (D4)">Teknologi Pakan Ternak (D4)</option>
                    <option value="Teknologi Rekayasa Komputer Jaringan (D4)">Teknologi Rekayasa Komputer Jaringan (D4)</option>
                    <option value="Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)">Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)</option>
                </select>
            </div>

            <div class="col-lg-4 col-md-12">
                <label for="angkatan" class="form-label fw-bold">Angkatan</label>
                <input type="number" name="angkatan" id="angkatan"
                       class="form-control" required placeholder="Masukkan Angkatan"
                       value="{{ old('angkatan') }}">
            </div>

            <div class="col-lg-4 col-md-12">
                <label for="ipk" class="form-label fw-bold">IPK</label>
                <input type="number" step="0.01" min="0" max="4"
                       name="ipk" id="ipk" class="form-control"
                       required placeholder="Masukkan IPK"
                       value="{{ old('ipk') }}">
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa fa-save me-2"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

{{-- ====== SCRIPT ====== --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.menu-toggle');
    const body = document.body;

    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            body.classList.toggle('sidebar-closed');
        });
    }
});
</script>
