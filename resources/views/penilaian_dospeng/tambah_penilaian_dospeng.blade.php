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
                    <i class="fa fa-plus-circle me-2"></i> Tambah Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian-dospeng.index') }}" class="btn btn-light border fw-bold">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('penilaian-dospeng.store') }}" method="POST">
                    @csrf

                    {{-- PILIH DOSEN DAN DATA MAHASISWA --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Dosen Penguji</label>
                        <select name="dosen_id" class="form-select mb-3" required>
                            <option value="">-- Pilih Dosen --</option>
                            <option value="1">Contoh Dosen</option>
                        </select>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Ujian</label>
                                <input type="date" name="tanggal_ujian" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Mahasiswa</label>
                                <input type="text" name="nama_mahasiswa" class="form-control" value="Maulida" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- JUDUL KOMPONEN PENILAIAN --}}
                    <div class="text-center mb-3">
                        <h5 class="fw-bold text-dark mb-1">Komponen Penilaian Dosen Penguji</h5>
                        <div class="mx-auto" style="width: 80px; height: 3px; background-color: #007bff;"></div>
                    </div>

                    {{-- KOMPONEN PENILAIAN --}}
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pemahaman Materi (15%)</label>
                            <input type="number" class="form-control text-center" name="pemahaman_materi" min="0" max="100" placeholder="0 - 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hasil yang Dicapai (40%)</label>
                            <input type="number" class="form-control text-center" name="hasil_dicapai" min="0" max="100" placeholder="0 - 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Objektivitas Menanggapi Pertanyaan (20%)</label>
                            <input type="number" class="form-control text-center" name="objektivitas" min="0" max="100" placeholder="0 - 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penulisan Laporan (15%)</label>
                            <input type="number" class="form-control text-center" name="penulisan_laporan" min="0" max="100" placeholder="0 - 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penyajian Presentasi (10%)</label>
                            <input type="number" class="form-control text-center" name="penyajian_presentasi" min="0" max="100" placeholder="0 - 100">
                        </div>
                    </div>

                    {{-- HASIL PERHITUNGAN --}}
                    <div class="mt-4 p-3 bg-light border rounded-3 text-center">
                        <p class="mb-1 fw-bold text-primary">
                            Nilai Total Dosen Penguji: <span id="total_nilai">0</span>
                        </p>
                        <p class="mb-0 fw-bold text-success">
                            Nilai Akhir (20% dari total): <span id="nilai_akhir">0</span>
                        </p>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-save me-2"></i> Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
