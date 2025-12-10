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
    }

    .form-control:focus, .form-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
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
                    <i class="fas fa-plus-circle me-2"></i> Tambah Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian-penguji.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
               <form action="{{ route('penilaian-penguji.store') }}" method="POST">
    @csrf

                    {{-- PILIH DOSEN DAN DATA MAHASISWA --}}
                    <div class="mb-4">
                        <label class="form-label">Pilih Dosen Penguji</label>
                        <select name="id_dosen" class="form-select mb-3" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosen as $d)
                                <option value="{{ $d->id_dosen }}">{{ $d->nip }} - {{ $d->nama }}</option>
                            @endforeach
                        </select>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Ujian</label>
                                <input type="date" name="tanggal_ujian" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input type="text" name="nama_mahasiswa" class="form-control" placeholder="Masukkan nama mahasiswa" required>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    {{-- KOMPONEN PENILAIAN --}}
                    <div class="section-title">
                        <h5>Komponen Penilaian Dosen Penguji</h5>
                        <div class="underline"></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Penyajian Presentasi (10%)</label>
                            <input type="number" class="form-control nilai-input nilai-inp" name="presentasi" 
                                   min="0" max="100" placeholder="0 - 100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pemahaman Materi (15%)</label>
                            <input type="number" class="form-control nilai-input nilai-inp" name="materi" 
                                   min="0" max="100" placeholder="0 - 100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hasil yang Dicapai (40%)</label>
                            <input type="number" class="form-control nilai-input nilai-inp" name="hasil" 
                                   min="0" max="100" placeholder="0 - 100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Objektivitas Menanggapi Pertanyaan (20%)</label>
                            <input type="number" class="form-control nilai-input nilai-inp" name="objektif" 
                                   min="0" max="100" placeholder="0 - 100" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penulisan Laporan (15%)</label>
                            <input type="number" class="form-control nilai-input nilai-inp" name="laporan" 
                                   min="0" max="100" placeholder="0 - 100" required>
                        </div>
                    </div>

                    {{-- HASIL PERHITUNGAN --}}
                    <div class="result-box">
                        <div class="result-item">
                            <span class="result-label">Nilai Total:</span>
                            <span class="result-value" id="total_nilai">0</span>
                        </div>
                        <div class="result-item final">
                            <span class="result-label">Nilai Akhir (20%):</span>
                            <span class="result-value" id="nilai_akhir">0</span>
                        </div>
                    </div>

                    {{-- TOMBOL SIMPAN --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-save me-2"></i> Simpan Penilaian
                        </button>
                    </div>
                </form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.nilai-input');
    const totalDisplay = document.getElementById('total_nilai');
    const akhirDisplay = document.getElementById('nilai_akhir');

    function hitungNilai() {
        let presentasi = parseFloat(document.querySelector('[name="presentasi"]').value) || 0;
        let materi = parseFloat(document.querySelector('[name="materi"]').value) || 0;
        let hasil = parseFloat(document.querySelector('[name="hasil"]').value) || 0;
        let objektif = parseFloat(document.querySelector('[name="objektif"]').value) || 0;
        let laporan = parseFloat(document.querySelector('[name="laporan"]').value) || 0;

        let total = (presentasi * 0.10) + 
                    (materi * 0.15) + 
                    (hasil * 0.40) + 
                    (objektif * 0.20) + 
                    (laporan * 0.15);
        
        let nilaiAkhir = total * 0.20;

        totalDisplay.textContent = total.toFixed(2);
        akhirDisplay.textContent = nilaiAkhir.toFixed(2);
    }

    inputs.forEach(input => {
        input.addEventListener('input', hitungNilai);
    });
});
</script>
