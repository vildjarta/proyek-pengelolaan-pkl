<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penilaian Perusahaan - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link rel="stylesheet" href="/assets/css/nilai.css">
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
        <h2>Detail Penilaian Perusahaan</h2>
        <div>
            <a href="{{ route('penilaian-perusahaan.edit', $penilaian->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('penilaian-perusahaan.index') }}" class="btn btn-secondary">
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
                <span>{{ $penilaian->id_mahasiswa }}</span>
            </div>
            <div class="detail-item">
                <strong>Nama Mahasiswa:</strong>
                <span>{{ $penilaian->mahasiswa->nama ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <strong>Program Studi:</strong>
                <span>{{ $penilaian->mahasiswa->prodi ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Penilaian Perusahaan -->
    <div class="form-section">
        <h3><i class="fas fa-building"></i> Penilaian dari Perusahaan</h3>
        <p style="margin-bottom: 15px; color: #666; font-size: 0.9em;">
            <i class="fas fa-info-circle"></i> Nilai mentah (0-100) dan nilai terbobot sesuai bobot persen.
        </p>
        <div class="detail-row">
            <div class="detail-item">
                <strong>Disiplin (Bobot 15%):</strong>
                <span>Nilai: {{ number_format($penilaian->disiplin, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_disiplin_terbobot, 2) }}%</span>
            </div>
            <div class="detail-item">
                <strong>Komunikasi (Bobot 10%):</strong>
                <span>Nilai: {{ number_format($penilaian->komunikasi, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_komunikasi_terbobot, 2) }}%</span>
            </div>
            <div class="detail-item">
                <strong>Kerja Tim (Bobot 15%):</strong>
                <span>Nilai: {{ number_format($penilaian->kerja_tim, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_kerja_tim_terbobot, 2) }}%</span>
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-item">
                <strong>Kerja Mandiri (Bobot 10%):</strong>
                <span>Nilai: {{ number_format($penilaian->kerja_mandiri, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_kerja_mandiri_terbobot, 2) }}%</span>
            </div>
            <div class="detail-item">
                <strong>Penampilan (Bobot 10%):</strong>
                <span>Nilai: {{ number_format($penilaian->penampilan, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_penampilan_terbobot, 2) }}%</span>
            </div>
            <div class="detail-item">
                <strong>Sikap/Etika (Bobot 20%):</strong>
                <span>Nilai: {{ number_format($penilaian->sikap_etika, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_sikap_etika_terbobot, 2) }}%</span>
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-item">
                <strong>Pengetahuan (Bobot 20%):</strong>
                <span>Nilai: {{ number_format($penilaian->pengetahuan, 2) }}/100</span>
                <span class="nilai-terbobot">Terbobot: {{ number_format($penilaian->nilai_pengetahuan_terbobot, 2) }}%</span>
            </div>
        </div>
        @if($penilaian->catatan)
        <div class="detail-item" style="width: 100%;">
            <strong>Catatan:</strong>
            <p style="margin-top: 8px; padding: 10px; background: #f5f5f5; border-radius: 5px;">
                {{ $penilaian->catatan }}
            </p>
        </div>
        @endif
    </div>

    <!-- Total Nilai -->
    <div class="total-nilai-box">
        <div class="total-row">
            <strong>NILAI TOTAL:</strong>
            <span class="nilai-besar">{{ number_format($penilaian->nilai_total, 2) }}</span>
        </div>
        <div class="total-row">
            <strong>NILAI HURUF:</strong>
            <span class="nilai-besar badge-nilai badge-{{ strtolower($penilaian->nilai_huruf) }}">
                {{ $penilaian->nilai_huruf }}
            </span>
        </div>
        <div class="total-row">
            <strong>SKOR:</strong>
            <span class="nilai-besar">{{ number_format($penilaian->skor, 2) }}</span>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <small class="text-muted">
            <i class="fas fa-clock"></i> Dibuat: {{ $penilaian->created_at->format('d/m/Y H:i') }}
            @if($penilaian->updated_at != $penilaian->created_at)
                | Diperbarui: {{ $penilaian->updated_at->format('d/m/Y H:i') }}
            @endif
        </small>
    </div>
</div>

<style>
.detail-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}
.detail-item {
    flex: 1;
    min-width: 250px;
}
.detail-item strong {
    display: block;
    color: #666;
    margin-bottom: 5px;
}
.detail-item span {
    display: block;
    color: #333;
    font-size: 1.1em;
}
.detail-item .nilai-terbobot {
    color: #28a745;
    font-weight: 500;
    font-size: 0.95em;
    margin-top: 3px;
}
</style>

</body>
</html>
