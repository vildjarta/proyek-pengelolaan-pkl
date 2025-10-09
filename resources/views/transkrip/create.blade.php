<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Transkrip - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
</head>
<body>

<div class="d-flex">
    @include('layout.header')
</div>

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Tambah Data Transkrip Kelayakan PKL</h2>
        <a href="{{ route('transkrip.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transkrip.store') }}" method="POST" id="transkripForm">
        @csrf
        
        <!-- Info Mahasiswa -->
        <div class="form-section">
            <h3><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>NIM: <span class="required">*</span></label>
                    <input type="text" name="nim" class="form-control" value="{{ old('nim') }}" required>
                </div>
                <div class="form-group">
                    <label>Nama Mahasiswa: <span class="required">*</span></label>
                    <input type="text" name="nama_mahasiswa" class="form-control" value="{{ old('nama_mahasiswa') }}" required>
                </div>
            </div>
        </div>

        <!-- Data Akademik -->
        <div class="form-section">
            <h3><i class="fas fa-graduation-cap"></i> Data Akademik</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>IPK: <span class="required">*</span></label>
                    <input type="number" step="0.01" name="ipk" class="form-control" min="0" max="4" value="{{ old('ipk') }}" required onchange="hitungKelayakan()">
                    <small class="form-text">IPK minimal 2.5 untuk layak PKL</small>
                </div>
                <div class="form-group">
                    <label>Total SKS D: <span class="required">*</span></label>
                    <input type="number" name="total_sks_d" class="form-control" min="0" value="{{ old('total_sks_d', 0) }}" required onchange="hitungKelayakan()">
                    <small class="form-text">Maksimal 6 SKS D untuk layak PKL</small>
                </div>
                <div class="form-group">
                    <label>Ada Nilai E: <span class="required">*</span></label>
                    <select name="has_e" class="form-control" required onchange="hitungKelayakan()">
                        <option value="0" {{ old('has_e') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('has_e') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    <small class="form-text">Tidak boleh ada nilai E untuk layak PKL</small>
                </div>
            </div>
        </div>

        <!-- Status Kelayakan -->
        <div class="total-nilai-box">
            <div class="total-row">
                <strong>STATUS KELAYAKAN PKL:</strong>
                <span id="status_kelayakan" class="nilai-besar">-</span>
            </div>
            <div id="keterangan_kelayakan" style="margin-top: 10px; font-size: 14px; color: #666;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Data
            </button>
            <a href="{{ route('transkrip.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
    function hitungKelayakan() {
        const ipk = parseFloat(document.querySelector('[name="ipk"]').value) || 0;
        const sksD = parseInt(document.querySelector('[name="total_sks_d"]').value) || 0;
        const hasE = document.querySelector('[name="has_e"]').value === '1';
        
        const statusElement = document.getElementById('status_kelayakan');
        const keteranganElement = document.getElementById('keterangan_kelayakan');
        
        let keterangan = [];
        let layak = true;
        
        // Cek IPK
        if (ipk < 2.5) {
            keterangan.push('❌ IPK kurang dari 2.5');
            layak = false;
        } else {
            keterangan.push('✓ IPK memenuhi syarat (≥ 2.5)');
        }
        
        // Cek SKS D
        if (sksD > 6) {
            keterangan.push('❌ Total SKS D lebih dari 6');
            layak = false;
        } else {
            keterangan.push('✓ Total SKS D memenuhi syarat (≤ 6)');
        }
        
        // Cek Nilai E
        if (hasE) {
            keterangan.push('❌ Terdapat nilai E');
            layak = false;
        } else {
            keterangan.push('✓ Tidak ada nilai E');
        }
        
        // Update tampilan
        if (layak) {
            statusElement.innerHTML = '<span style="color: green;"><i class="fas fa-check-circle"></i> LAYAK</span>';
            statusElement.style.color = 'green';
        } else {
            statusElement.innerHTML = '<span style="color: red;"><i class="fas fa-times-circle"></i> TIDAK LAYAK</span>';
            statusElement.style.color = 'red';
        }
        
        keteranganElement.innerHTML = keterangan.join('<br>');
    }

    // Hitung saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        hitungKelayakan();
        
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

</body>
</html>
