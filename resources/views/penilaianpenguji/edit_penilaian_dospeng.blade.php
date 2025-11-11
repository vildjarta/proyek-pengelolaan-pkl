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
            <div class="card-header bg-warning text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-edit me-2"></i> Edit Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian.index') }}" class="btn btn-light border fw-bold text-warning">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST" id="penilaianForm">
                    @csrf
                    @method('PUT')

                    {{-- PILIH DOSEN DAN DATA MAHASISWA --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Dosen Penguji</label>
                        <select name="id_penguji" class="form-select mb-3" required>
                            <option value="">-- Pilih Dosen --</option>
                            <option value="{{ $penilaian->dosen->id_penguji }}" selected>
                                {{ $penilaian->dosen->nip }} - {{ $penilaian->dosen->nama_dosen }}
                            </option>
                        </select>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Ujian</label>
                                <input type="date" name="tanggal_ujian" class="form-control"
                                       value="{{ $penilaian->tanggal_ujian }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Mahasiswa</label>
                                <input type="text" name="nama_mahasiswa" class="form-control"
                                       value="{{ $penilaian->nama_mahasiswa }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- JUDUL KOMPONEN PENILAIAN --}}
                    <div class="text-center mb-3">
                        <h5 class="fw-bold text-dark mb-1">Komponen Penilaian Dosen Penguji</h5>
                        <div class="mx-auto" style="width: 80px; height: 3px; background-color: #ffc107;"></div>
                    </div>

                    {{-- KOMPONEN PENILAIAN --}}
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pemahaman Materi (15%)</label>
                            <input type="number" class="form-control text-center nilai" id="materi"
                                   name="materi" min="0" max="100" value="{{ $penilaian->materi }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hasil yang Dicapai (40%)</label>
                            <input type="number" class="form-control text-center nilai" id="hasil"
                                   name="hasil" min="0" max="100" value="{{ $penilaian->hasil }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Objektivitas Menanggapi Pertanyaan (20%)</label>
                            <input type="number" class="form-control text-center nilai" id="objektif"
                                   name="objektif" min="0" max="100" value="{{ $penilaian->objektif }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penulisan Laporan (15%)</label>
                            <input type="number" class="form-control text-center nilai" id="laporan"
                                   name="laporan" min="0" max="100" value="{{ $penilaian->laporan }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penyajian Presentasi (10%)</label>
                            <input type="number" class="form-control text-center nilai" id="presentasi"
                                   name="presentasi" min="0" max="100" value="{{ $penilaian->presentasi }}" required>
                        </div>
                    </div>

                    {{-- HASIL PERHITUNGAN --}}
                    <div class="mt-4 p-3 bg-light border rounded-3 text-center">
                        <p class="mb-1 fw-bold text-warning">
                            Nilai Total Dosen Penguji: <span id="totalNilai">{{ $penilaian->total_nilai ?? 0 }}</span>
                        </p>
                        <p class="mb-0 fw-bold text-success">
                            Nilai Akhir (20% dari total): <span id="nilai20">{{ $penilaian->nilai_akhir ?? 0 }}</span>
                        </p>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-warning rounded-pill px-4 text-white fw-bold">
                            <i class="fa fa-save me-2"></i> Update Penilaian
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
    const fields = document.querySelectorAll('.nilai');
    const totalDisplay = document.getElementById('totalNilai');
    const nilai20Display = document.getElementById('nilai20');

    function hitungTotal() {
        const presentasi = parseFloat(document.getElementById('presentasi').value) || 0;
        const materi = parseFloat(document.getElementById('materi').value) || 0;
        const hasil = parseFloat(document.getElementById('hasil').value) || 0;
        const objektif = parseFloat(document.getElementById('objektif').value) || 0;
        const laporan = parseFloat(document.getElementById('laporan').value) || 0;

        const total = (presentasi * 0.10) +
                      (materi * 0.15) +
                      (hasil * 0.40) +
                      (objektif * 0.20) +
                      (laporan * 0.15);

        const nilai20 = total * 0.20;

        totalDisplay.textContent = total.toFixed(2);
        nilai20Display.textContent = nilai20.toFixed(2);
    }

    fields.forEach(f => f.addEventListener('input', hitungTotal));
    hitungTotal();
});
</script>
