<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transkrip - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link rel="stylesheet" href="/assets/css/transkrip.css">
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
        <h2>Detail Transkrip Kelayakan PKL</h2>
        <div>
            <a href="{{ route('transkrip.edit', $transkrip->id) }}" class="btn btn-primary" style="margin-right: 10px;">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('transkrip.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Mahasiswa -->
    <div class="form-section">
        <h3><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
        <div class="detail-row">
            <div class="detail-item">
                <strong>NIM:</strong>
                <span>{{ $transkrip->nim }}</span>
            </div>
            <div class="detail-item">
                <strong>Nama Mahasiswa:</strong>
                <span>{{ $transkrip->nama_mahasiswa }}</span>
            </div>
        </div>
    </div>

    <!-- Data Akademik -->
    <div class="form-section">
        <h3><i class="fas fa-graduation-cap"></i> Data Akademik</h3>
        <div class="detail-row">
            <div class="detail-item">
                <strong>IPK:</strong>
                <span class="nilai-display">{{ number_format($transkrip->ipk, 2) }}</span>
            </div>
            <div class="detail-item">
                <strong>Total SKS D:</strong>
                <span class="nilai-display">{{ $transkrip->total_sks_d }}</span>
            </div>
            <div class="detail-item">
                <strong>Ada Nilai E:</strong>
                <span class="nilai-display">{{ $transkrip->has_e ? 'Ya' : 'Tidak' }}</span>
            </div>
        </div>
    </div>

    <!-- Analisis Kelayakan -->
    <div class="form-section">
        <h3><i class="fas fa-clipboard-check"></i> Analisis Kelayakan PKL</h3>
        <div class="detail-row">
            <div class="detail-item" style="width: 100%;">
                <strong>Status Kelayakan:</strong>
                @if($transkrip->eligible)
                    <span class="status-badge status-layak" style="font-size: 18px; padding: 10px 20px;">
                        <i class="fas fa-check-circle"></i> LAYAK PKL
                    </span>
                @else
                    <span class="status-badge status-tidak-layak" style="font-size: 18px; padding: 10px 20px;">
                        <i class="fas fa-times-circle"></i> TIDAK LAYAK PKL
                    </span>
                @endif
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px;">
            <h4 style="margin-top: 0;">Kriteria Kelayakan:</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li style="margin: 8px 0;">
                    @if($transkrip->ipk >= 2.5)
                        <i class="fas fa-check-circle" style="color: green;"></i>
                    @else
                        <i class="fas fa-times-circle" style="color: red;"></i>
                    @endif
                    <strong>IPK:</strong> {{ number_format($transkrip->ipk, 2) }} 
                    (Minimal 2.5)
                </li>
                <li style="margin: 8px 0;">
                    @if($transkrip->total_sks_d <= 6)
                        <i class="fas fa-check-circle" style="color: green;"></i>
                    @else
                        <i class="fas fa-times-circle" style="color: red;"></i>
                    @endif
                    <strong>Total SKS D:</strong> {{ $transkrip->total_sks_d }} 
                    (Maksimal 6 SKS)
                </li>
                <li style="margin: 8px 0;">
                    @if(!$transkrip->has_e)
                        <i class="fas fa-check-circle" style="color: green;"></i>
                    @else
                        <i class="fas fa-times-circle" style="color: red;"></i>
                    @endif
                    <strong>Nilai E:</strong> {{ $transkrip->has_e ? 'Ada' : 'Tidak Ada' }} 
                    (Tidak boleh ada nilai E)
                </li>
            </ul>
        </div>
    </div>

    <!-- Info Tambahan -->
    <div class="form-section">
        <h3><i class="fas fa-info-circle"></i> Informasi Tambahan</h3>
        <div class="detail-row">
            <div class="detail-item">
                <strong>Tanggal Input:</strong>
                <span>{{ \Carbon\Carbon::parse($transkrip->created_at)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="detail-item">
                <strong>Terakhir Diupdate:</strong>
                <span>{{ \Carbon\Carbon::parse($transkrip->updated_at)->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Aksi -->
    <div class="form-actions">
        <a href="{{ route('transkrip.edit', $transkrip->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Data
        </a>
        <form action="{{ route('transkrip.destroy', $transkrip->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Hapus Data
            </button>
        </form>
        <a href="{{ route('transkrip.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<style>
.detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 15px;
}

.detail-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.detail-item strong {
    display: block;
    margin-bottom: 8px;
    color: #495057;
    font-size: 14px;
}

.detail-item span {
    display: block;
    font-size: 16px;
    color: #212529;
    font-weight: 500;
}

.nilai-display {
    font-size: 20px !important;
    font-weight: bold !important;
    color: #007bff !important;
}
</style>

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

</body>
</html>
