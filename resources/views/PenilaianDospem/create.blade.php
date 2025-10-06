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

        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf
            {{-- PERUBAHAN DI SINI --}}
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

            {{-- ... Sisa form lainnya tetap sama ... --}}
            <div class="form-group">
                <label for="presentasi">Presentasi (0-100)</label>
                <input type="number" name="presentasi" id="presentasi" class="form-control" min="0" max="100" value="{{ old('presentasi') }}" required>
            </div>

            <div class="form-group">
                <label for="laporan">Laporan Tertulis (0-100)</label>
                <input type="number" name="laporan" id="laporan" class="form-control" min="0" max="100" value="{{ old('laporan') }}" required>
            </div>

            <div class="form-group">
                <label for="penguasaan">Penguasaan Materi (0-100)</label>
                <input type="number" name="penguasaan" id="penguasaan" class="form-control" min="0" max="100" value="{{ old('penguasaan') }}" required>
            </div>

            <div class="form-group">
                <label for="sikap">Sikap & Etika (0-100)</label>
                <input type="number" name="sikap" id="sikap" class="form-control" min="0" max="100" value="{{ old('sikap') }}" required>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan Dosen Pembimbing (Opsional)</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Simpan Penilaian</button>
                <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
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