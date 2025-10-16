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
            <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian.index') }}" class="btn btn-light text-success fw-bold">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('penilaian.store') }}" method="POST" id="penilaianForm">
                    @csrf
                    <div class="row g-3">
                        {{-- Data Umum --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pilih Dosen Penguji</label>
                            <select name="id_penguji" class="form-control" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->id_penguji }}">
                                    {{ $d->nip }} - {{ $d->nama_dosen }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Mahasiswa</label>
                            <input type="text" name="nama_mahasiswa" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Ujian</label>
                            <input type="date" name="tanggal_ujian" class="form-control" required>
                        </div>

                        {{-- Komponen Penilaian --}}
                        <h5 class="fw-bold mt-4">Komponen Penilaian Dosen Penguji</h5>

                        <div class="col-md-6">
                            <label class="form-label">Penyajian Presentasi (10%)</label>
                            <input type="number" name="presentasi" class="form-control nilai" id="presentasi" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pemahaman Materi (15%)</label>
                            <input type="number" name="materi" class="form-control nilai" id="materi" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hasil yang Dicapai (40%)</label>
                            <input type="number" name="hasil" class="form-control nilai" id="hasil" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Objektivitas Menanggapi Pertanyaan (20%)</label>
                            <input type="number" name="objektif" class="form-control nilai" id="objektif" min="0" max="100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penulisan Laporan (15%)</label>
                            <input type="number" name="laporan" class="form-control nilai" id="laporan" min="0" max="100" required>
                        </div>

                        {{-- Hasil Akhir --}}
                        <div class="col-md-12 mt-4">
                            <div class="result-box p-3 rounded text-center border border-primary bg-light">
                                <strong>Nilai Total Dosen Penguji: </strong>
                                <span id="totalNilai" class="fw-bold text-primary">0</span>
                                <div class="sub-result mt-2">
                                    <strong>Nilai Akhir (20% dari total): </strong>
                                    <span id="nilai20" class="fw-bold text-success">0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fa fa-save me-2"></i> Simpan Penilaian
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

        // Hitung total berdasarkan bobot
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

    document.getElementById('penilaianForm').addEventListener('submit', function(e) {
        const total = parseFloat(totalDisplay.textContent);
        const nilai20 = parseFloat(nilai20Display.textContent);
        if (isNaN(total) || isNaN(nilai20)) {
            e.preventDefault();
            alert('Harap isi semua nilai sebelum menyimpan!');
        }
    });
});
</script>
