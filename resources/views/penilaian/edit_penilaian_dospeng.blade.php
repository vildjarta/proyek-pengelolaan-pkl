{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== CSS TAMBAHAN ====== --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

{{-- ====== KONTEN UTAMA ====== --}}
<div class="main-content-wrapper">
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-warning text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-edit me-2"></i> Edit Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian.index') }}" class="btn btn-light text-warning fw-bold">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST" id="penilaianForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        {{-- Data Umum --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ $penilaian->nip }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Dosen</label>
                            <input type="text" name="nama_dosen" class="form-control" value="{{ $penilaian->nama_dosen }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Mahasiswa</label>
                            <input type="text" name="nama_mahasiswa" class="form-control" value="{{ $penilaian->nama_mahasiswa }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Ujian</label>
                            <input type="date" name="tanggal_ujian" class="form-control" value="{{ $penilaian->tanggal_ujian }}" required>
                        </div>

                        {{-- Komponen Penilaian --}}
                        <h5 class="fw-bold mt-4">Komponen Penilaian Dosen Penguji</h5>

                        <div class="col-md-6">
                            <label class="form-label">Penyajian Presentasi (10%)</label>
                            <input type="number" name="presentasi" class="form-control nilai" id="presentasi"
                                   value="{{ $penilaian->presentasi }}" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pemahaman Materi (15%)</label>
                            <input type="number" name="materi" class="form-control nilai" id="materi"
                                   value="{{ $penilaian->materi }}" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hasil yang Dicapai (40%)</label>
                            <input type="number" name="hasil" class="form-control nilai" id="hasil"
                                   value="{{ $penilaian->hasil }}" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Objektivitas Menanggapi Pertanyaan (20%)</label>
                            <input type="number" name="objektif" class="form-control nilai" id="objektif"
                                   value="{{ $penilaian->objektif }}" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penulisan Laporan (15%)</label>
                            <input type="number" name="laporan" class="form-control nilai" id="laporan"
                                   value="{{ $penilaian->laporan }}" min="0" max="100" required>
                        </div>

                        {{-- Hasil Akhir --}}
                        <div class="col-md-12 mt-4">
                            <div class="result-box p-3 rounded text-center border border-primary bg-light">
                                <strong>Nilai Total Dosen Penguji: </strong>
                                <span id="totalNilai" class="fw-bold text-primary">{{ $penilaian->total_nilai ?? 0 }}</span>
                                <div class="sub-result mt-2">
                                    <strong>Nilai Akhir (20% dari total): </strong>
                                    <span id="nilai20" class="fw-bold text-success">{{ $penilaian->nilai_akhir ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 text-white fw-bold">
                                <i class="fa fa-save me-2"></i> Update Penilaian
                            </button>
                        </div>
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
    const inputFields = document.querySelectorAll('.nilai');
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

    inputFields.forEach(field => {
        field.addEventListener('input', hitungTotal);
    });

    hitungTotal(); // langsung hitung saat halaman dibuka
});
</script>
