<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Nilai Mahasiswa - Sistem PKL JOZZ</title>
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
        <h2>Detail Nilai Mahasiswa PKL</h2>
        <div>
            <a href="{{ route('nilai.edit', $nilai->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Mahasiswa -->
    <div class="detail-card">
        <h3><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <strong>ID Nilai:</strong>
                <span>{{ $nilai->id_nilai }}</span>
            </div>
            <div class="detail-item">
                <strong>NIM:</strong>
                <span>{{ $nilai->id_mahasiswa }}</span>
            </div>
            <div class="detail-item">
                <strong>Nama Mahasiswa:</strong>
                <span>{{ $nilai->mahasiswa->nama ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <strong>Tanggal Input:</strong>
                <span>{{ \Carbon\Carbon::parse($nilai->created_at)->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Penilaian Pembimbing Lapangan -->
    <div class="detail-card">
        <h3><i class="fas fa-briefcase"></i> Penilaian Pembimbing Lapangan (Bobot 50%)</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th>Nilai</th>
                    <th>Maksimal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Disiplin</td>
                    <td>{{ number_format($nilai->disiplin, 2) }}%</td>
                    <td>15%</td>
                </tr>
                <tr>
                    <td>Komunikasi</td>
                    <td>{{ number_format($nilai->komunikasi, 2) }}%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Kerja Tim</td>
                    <td>{{ number_format($nilai->kerja_tim, 2) }}%</td>
                    <td>15%</td>
                </tr>
                <tr>
                    <td>Kerja Mandiri</td>
                    <td>{{ number_format($nilai->kerja_mandiri, 2) }}%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Penampilan</td>
                    <td>{{ number_format($nilai->penampilan, 2) }}%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Sikap/Etika</td>
                    <td>{{ number_format($nilai->sikap_etika_lapangan, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr>
                    <td>Pengetahuan</td>
                    <td>{{ number_format($nilai->pengetahuan, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr class="subtotal-row">
                    <td><strong>Subtotal</strong></td>
                    <td><strong>{{ number_format($nilai->nilai_pembimbing, 2) }}%</strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
        @if($nilai->catatan_pembimbing)
        <div class="catatan-box">
            <strong>Catatan:</strong>
            <p>{{ $nilai->catatan_pembimbing }}</p>
        </div>
        @endif
    </div>

    <!-- Penilaian Dosen Pembimbing -->
    <div class="detail-card">
        <h3><i class="fas fa-chalkboard-teacher"></i> Penilaian Dosen Pembimbing (Bobot 30%)</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th>Nilai</th>
                    <th>Maksimal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Penguasaan Teori</td>
                    <td>{{ number_format($nilai->penguasaan_teori, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr>
                    <td>Kemampuan Analisis dan Pemecahan Masalah</td>
                    <td>{{ number_format($nilai->kemampuan_analisis, 2) }}%</td>
                    <td>25%</td>
                </tr>
                <tr>
                    <td>Keaktifan Bimbingan</td>
                    <td>{{ number_format($nilai->keaktifan_bimbingan, 2) }}%</td>
                    <td>15%</td>
                </tr>
                <tr>
                    <td>Kemampuan Penulisan Laporan</td>
                    <td>{{ number_format($nilai->kemampuan_penulisan_laporan, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr>
                    <td>Sikap/Etika</td>
                    <td>{{ number_format($nilai->sikap_etika_dospem, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr class="subtotal-row">
                    <td><strong>Subtotal</strong></td>
                    <td><strong>{{ number_format($nilai->nilai_dospem, 2) }}%</strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
        @if($nilai->catatan_dospem)
        <div class="catatan-box">
            <strong>Catatan:</strong>
            <p>{{ $nilai->catatan_dospem }}</p>
        </div>
        @endif
    </div>

    <!-- Penilaian Penguji -->
    <div class="detail-card">
        <h3><i class="fas fa-clipboard-check"></i> Penilaian Penguji (Bobot 20%)</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th>Nilai</th>
                    <th>Maksimal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Penyajian Presentasi</td>
                    <td>{{ number_format($nilai->penyajian_presentasi, 2) }}%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Pemahaman Materi</td>
                    <td>{{ number_format($nilai->pemahaman_materi, 2) }}%</td>
                    <td>15%</td>
                </tr>
                <tr>
                    <td>Hasil yang Dicapai</td>
                    <td>{{ number_format($nilai->hasil_yang_dicapai, 2) }}%</td>
                    <td>40%</td>
                </tr>
                <tr>
                    <td>Objektivitas Menanggapi Pertanyaan</td>
                    <td>{{ number_format($nilai->objektivitas_menangapi, 2) }}%</td>
                    <td>20%</td>
                </tr>
                <tr>
                    <td>Penulisan Laporan</td>
                    <td>{{ number_format($nilai->penulisan_laporan, 2) }}%</td>
                    <td>15%</td>
                </tr>
                <tr class="subtotal-row">
                    <td><strong>Subtotal</strong></td>
                    <td><strong>{{ number_format($nilai->nilai_penguji, 2) }}%</strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
        @if($nilai->catatan_penguji)
        <div class="catatan-box">
            <strong>Catatan:</strong>
            <p>{{ $nilai->catatan_penguji }}</p>
        </div>
        @endif
    </div>

    <!-- Total Nilai -->
    <div class="total-nilai-card">
        <h3>Hasil Akhir Penilaian</h3>
        <div class="total-grid">
            <div class="total-item">
                <div class="total-label">Nilai Total</div>
                <div class="total-value">{{ number_format($nilai->nilai_total, 2) }}</div>
            </div>
            <div class="total-item">
                <div class="total-label">Nilai Huruf</div>
                <div class="total-value badge-nilai badge-{{ strtolower($nilai->nilai_huruf) }}">
                    {{ $nilai->nilai_huruf }}
                </div>
            </div>
            <div class="total-item">
                <div class="total-label">Skor</div>
                <div class="total-value">{{ number_format($nilai->skor, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('nilai.edit', $nilai->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Nilai
        </a>
        <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> Kembali ke Daftar
        </a>
        <button onclick="window.print()" class="btn btn-info">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
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

</body>
</html>
