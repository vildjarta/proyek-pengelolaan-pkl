@include('layout.header')
@include('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/form-penilaian.css') }}">

<div class="main-content-wrapper">
    <div class="content">
        <h2>Tambah Penilaian Mahasiswa</h2>
        
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: .25rem; margin-bottom: 1rem;">
            <strong>Whoops! Terjadi kesalahan.</strong>
            <ul style="margin-top: 10px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ... (kode header dan lainnya tetap sama) ... --}}
    <form action="{{ route('penilaian.store') }}" method="POST">
        @csrf
        {{-- Grup form Nama Mahasiswa dan Judul tetap sama --}}
        <div class="form-group">
            <label for="nama_mahasiswa">Nama Mahasiswa</label>
            <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" class="form-control" 
                value="{{ old('nama_mahasiswa') }}" placeholder="Ketik nama mahasiswa..." 
                list="mahasiswa-list" autocomplete="off" required>
            
            <datalist id="mahasiswa-list">
                @foreach($mahasiswa as $mhs)
                    <option value="{{ $mhs->nama }}">
                @endforeach
            </datalist>
        </div>

        <div class="form-group">
            <label for="judul">Judul PKL / Seminar</label>
            <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
        </div>

        {{-- KOLOM PENILAIAN BARU --}}
        <div class="form-group">
            <label for="penguasaan_teori">Penguasaan Teori (Bobot: 20%)</label>
            <input type="number" name="penguasaan_teori" id="penguasaan_teori" class="form-control" min="0" max="100" value="{{ old('penguasaan_teori') }}" required>
        </div>

        <div class="form-group">
            <label for="analisis_pemecahan_masalah">Kemampuan Analisis & Pemecahan Masalah (Bobot: 25%)</label>
            <input type="number" name="analisis_pemecahan_masalah" id="analisis_pemecahan_masalah" class="form-control" min="0" max="100" value="{{ old('analisis_pemecahan_masalah') }}" required>
        </div>

        <div class="form-group">
            <label for="keaktifan_bimbingan">Keaktifan Bimbingan (Bobot: 15%)</label>
            <input type="number" name="keaktifan_bimbingan" id="keaktifan_bimbingan" class="form-control" min="0" max="100" value="{{ old('keaktifan_bimbingan') }}" required>
        </div>

        <div class="form-group">
            <label for="penulisan_laporan">Kemampuan Penulisan Laporan (Bobot: 20%)</label>
            <input type="number" name="penulisan_laporan" id="penulisan_laporan" class="form-control" min="0" max="100" value="{{ old('penulisan_laporan') }}" required>
        </div>
        
        <div class="form-group">
            <label for="sikap">Sikap & Etika (Bobot: 20%)</label>
            <input type="number" name="sikap" id="sikap" class="form-control" min="0" max="100" value="{{ old('sikap') }}" required>
        </div>

        {{-- Catatan dan tombol tetap sama --}}
        <div class="form-group">
            <label for="catatan">Catatan Dosen Pembimbing (Opsional)</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan Penilaian</button>
            <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
    {{-- ... (sisa kode file) ... --}}
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');
        
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                body.classList.toggle('sidebar-closed');
            });
        }
        
        if (userinfo) {
            userinfo.addEventListener('click', function(e) {
                e.preventDefault(); 
                profileWrapper.classList.toggle('active');
            });
            
            document.addEventListener('click', function(e) {
                if (!profileWrapper.contains(e.target) && profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
    });
</script>
</div>
<script>
    function confirmSubmit() {
        return confirm("Apakah Anda yakin ingin Membuat Penilaian ini?");
    }
</script>