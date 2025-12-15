{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== CSS TAMBAHAN ====== --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

<style>
    .main-content-wrapper {
        margin-left: 250px;
        padding: 30px;
        min-height: 100vh;
        background-color: #f5f5f5;
    }

    .card {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header {
        background: #1976d2;
        border: none;
        padding: 25px 30px;
    }

    .card-header h4 {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .btn-back {
        background: white;
        color: #1976d2;
        border: none;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #f5f5f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.2);
        color: #1565c0;
    }

    .card-body {
        padding: 40px;
        background: white;
    }

    .form-section {
        background: #fafbfc;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 25px;
        border-left: 4px solid #1976d2;
    }

    .form-section-title {
        color: #1976d2;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-label {
        font-weight: 600;
        color: #424242;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        background: white;
    }

    .form-control:readonly {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
        margin: 30px 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 30px;
    }

    .section-title h5 {
        font-weight: 700;
        color: #1976d2;
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .section-title .underline {
        width: 80px;
        height: 3px;
        background: #1976d2;
        margin: 0 auto;
    }

    .nilai-input {
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .result-box {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px;
        margin-top: 30px;
    }

    .result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        font-size: 1.1rem;
    }

    .result-label {
        font-weight: 600;
        color: #424242;
    }

    .result-value {
        font-weight: 700;
        font-size: 1.3rem;
        color: #1976d2;
    }

    .result-item.final {
        border-top: 2px solid #e0e0e0;
        margin-top: 12px;
        padding-top: 20px;
    }

    .result-item.final .result-value {
        color: #4caf50;
        font-size: 1.5rem;
    }

    .btn-submit {
        background: #1976d2;
        color: white;
        border: none;
        font-weight: 700;
        padding: 12px 40px;
        border-radius: 25px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #1565c0;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 118, 210, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .main-content-wrapper {
            margin-left: 0;
            padding: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .card-header h4 {
            font-size: 1.2rem;
        }
    }
</style>

{{-- ====== KONTEN UTAMA ====== --}}
<div class="main-content-wrapper">
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white">
                    <i class="fas fa-edit me-2"></i> Edit Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian-penguji.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('penilaian-penguji.update', $penilaian->id) }}" method="POST" id="penilaianForm">
                    @csrf
                    @method('PUT')

                    {{-- INFORMASI PENGUJI & MAHASISWA --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user-tie"></i> Informasi Penguji & Mahasiswa
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dosen Penguji <span style="color: red;">*</span></label>
                            <select name="id_dosen" class="form-select" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->id_dosen }}" {{ $penilaian->id_dosen == $d->id_dosen ? 'selected' : '' }}>
                                        {{ $d->nip }} - {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa <span style="color: red;">*</span></label>
                            <select name="nama_mahasiswa" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->nama }}" {{ $penilaian->nama_mahasiswa == $mhs->nama ? 'selected' : '' }}>
                                        {{ $mhs->nim }} - {{ $mhs->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Tanggal Ujian <span style="color: red;">*</span></label>
                            <input type="date" name="tanggal_ujian" class="form-control"
                                   value="{{ $penilaian->tanggal_ujian }}" required>
                        </div>
                    </div>

                    {{-- KOMPONEN PENILAIAN --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-clipboard-check"></i> Komponen Penilaian
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Penyajian Presentasi (10%) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai-input nilai" id="presentasi"
                                       name="presentasi" min="0" max="100" value="{{ $penilaian->presentasi }}" 
                                       placeholder="0-100" required>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Pemahaman Materi (15%) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai-input nilai" id="materi"
                                       name="materi" min="0" max="100" value="{{ $penilaian->materi }}" 
                                       placeholder="0-100" required>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Hasil yang Dicapai (40%) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai-input nilai" id="hasil"
                                       name="hasil" min="0" max="100" value="{{ $penilaian->hasil }}" 
                                       placeholder="0-100" required>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Objektivitas Pertanyaan (20%) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai-input nilai" id="objektif"
                                       name="objektif" min="0" max="100" value="{{ $penilaian->objektif }}" 
                                       placeholder="0-100" required>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label class="form-label">Penulisan Laporan (15%) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai-input nilai" id="laporan"
                                       name="laporan" min="0" max="100" value="{{ $penilaian->laporan }}" 
                                       placeholder="0-100" required>
                            </div>
                        </div>
                    </div>

                    {{-- HASIL PERHITUNGAN --}}
                    <div class="result-box">
                        <div class="result-item">
                            <span class="result-label">Nilai Total Dosen Penguji:</span>
                            <span class="result-value" id="totalNilai">{{ $penilaian->total_nilai ?? 0 }}</span>
                        </div>
                        <div class="result-item final">
                            <span class="result-label">Nilai Akhir (20% dari total):</span>
                            <span class="result-value" id="nilai20">{{ $penilaian->nilai_akhir ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-save me-2"></i> Update Penilaian
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
