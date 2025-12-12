<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penilaian Perusahaan - Sistem PKL JOZZ</title>
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
        <h2>Tambah Penilaian Perusahaan</h2>
        <a href="{{ route('penilaian-perusahaan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penilaian-perusahaan.store') }}" method="POST" id="penilaianForm">
        @csrf

        <!-- Info Mahasiswa -->
        <div class="form-section">
            <h3><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
            <div class="form-group">
                <label>Pilih Mahasiswa: <span class="required">*</span></label>
                <select name="id_mahasiswa" class="form-control" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswa as $mhs)
                        <option value="{{ $mhs->nim }}" {{ old('id_mahasiswa') == $mhs->nim ? 'selected' : '' }}>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Penilaian Perusahaan (50%) -->
        <div class="form-section">
            <h3><i class="fas fa-building"></i> Penilaian dari Perusahaan (Total 50%)</h3>
            <p style="margin-bottom: 15px; color: #666; font-size: 0.9em;">
                <i class="fas fa-info-circle"></i> Masukkan nilai 0-100 untuk setiap komponen. Sistem akan menghitung otomatis sesuai bobot.
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label>Disiplin (0-100) - Bobot 15%:</label>
                    <input type="number" step="0.01" name="disiplin" class="form-control nilai-input" min="0" max="100" value="{{ old('disiplin', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="disiplin_terbobot">0.00</span>%</small>
                </div>
                <div class="form-group">
                    <label>Komunikasi (0-100) - Bobot 10%:</label>
                    <input type="number" step="0.01" name="komunikasi" class="form-control nilai-input" min="0" max="100" value="{{ old('komunikasi', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="komunikasi_terbobot">0.00</span>%</small>
                </div>
                <div class="form-group">
                    <label>Kerja Tim (0-100) - Bobot 15%:</label>
                    <input type="number" step="0.01" name="kerja_tim" class="form-control nilai-input" min="0" max="100" value="{{ old('kerja_tim', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="kerja_tim_terbobot">0.00</span>%</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Kerja Mandiri (0-100) - Bobot 10%:</label>
                    <input type="number" step="0.01" name="kerja_mandiri" class="form-control nilai-input" min="0" max="100" value="{{ old('kerja_mandiri', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="kerja_mandiri_terbobot">0.00</span>%</small>
                </div>
                <div class="form-group">
                    <label>Penampilan (0-100) - Bobot 10%:</label>
                    <input type="number" step="0.01" name="penampilan" class="form-control nilai-input" min="0" max="100" value="{{ old('penampilan', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="penampilan_terbobot">0.00</span>%</small>
                </div>
                <div class="form-group">
                    <label>Sikap/Etika (0-100) - Bobot 20%:</label>
                    <input type="number" step="0.01" name="sikap_etika" class="form-control nilai-input" min="0" max="100" value="{{ old('sikap_etika', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="sikap_etika_terbobot">0.00</span>%</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Pengetahuan (0-100) - Bobot 20%:</label>
                    <input type="number" step="0.01" name="pengetahuan" class="form-control nilai-input" min="0" max="100" value="{{ old('pengetahuan', 0) }}" oninput="hitungTotal()">
                    <small class="nilai-terbobot">Nilai: <span id="pengetahuan_terbobot">0.00</span>%</small>
                </div>
            </div>
            <div class="form-group">
                <label>Catatan Perusahaan:</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <!-- Total Nilai -->
        <div class="total-nilai-box">
            <div class="total-row">
                <strong>NILAI TOTAL:</strong>
                <span id="nilai_total" class="nilai-besar">0.00</span>
            </div>
            <div class="total-row">
                <strong>NILAI HURUF:</strong>
                <span id="nilai_huruf" class="nilai-besar">-</span>
            </div>
            <div class="total-row">
                <strong>SKOR:</strong>
                <span id="skor" class="nilai-besar">0.00</span>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Penilaian
            </button>
            <a href="{{ route('penilaian-perusahaan.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
    // Bobot masing-masing komponen
    const BOBOT = {
        disiplin: 15,
        komunikasi: 10,
        kerja_tim: 15,
        kerja_mandiri: 10,
        penampilan: 10,
        sikap_etika: 20,
        pengetahuan: 20
    };

    function hitungNilaiDenganBobot(nilaiMentah, bobot) {
        return (nilaiMentah / 100) * bobot;
    }

    function hitungTotal() {
        // Ambil nilai mentah (0-100)
        const disiplin = parseFloat(document.querySelector('[name="disiplin"]').value) || 0;
        const komunikasi = parseFloat(document.querySelector('[name="komunikasi"]').value) || 0;
        const kerja_tim = parseFloat(document.querySelector('[name="kerja_tim"]').value) || 0;
        const kerja_mandiri = parseFloat(document.querySelector('[name="kerja_mandiri"]').value) || 0;
        const penampilan = parseFloat(document.querySelector('[name="penampilan"]').value) || 0;
        const sikap_etika = parseFloat(document.querySelector('[name="sikap_etika"]').value) || 0;
        const pengetahuan = parseFloat(document.querySelector('[name="pengetahuan"]').value) || 0;

        // Hitung nilai terbobot
        const disiplinTerbobot = hitungNilaiDenganBobot(disiplin, BOBOT.disiplin);
        const komunikasiTerbobot = hitungNilaiDenganBobot(komunikasi, BOBOT.komunikasi);
        const kerjaTimTerbobot = hitungNilaiDenganBobot(kerja_tim, BOBOT.kerja_tim);
        const kerjaMandiriTerbobot = hitungNilaiDenganBobot(kerja_mandiri, BOBOT.kerja_mandiri);
        const penampilanTerbobot = hitungNilaiDenganBobot(penampilan, BOBOT.penampilan);
        const sikapEtikaTerbobot = hitungNilaiDenganBobot(sikap_etika, BOBOT.sikap_etika);
        const pengetahuanTerbobot = hitungNilaiDenganBobot(pengetahuan, BOBOT.pengetahuan);

        // Tampilkan nilai terbobot
        document.getElementById('disiplin_terbobot').textContent = disiplinTerbobot.toFixed(2);
        document.getElementById('komunikasi_terbobot').textContent = komunikasiTerbobot.toFixed(2);
        document.getElementById('kerja_tim_terbobot').textContent = kerjaTimTerbobot.toFixed(2);
        document.getElementById('kerja_mandiri_terbobot').textContent = kerjaMandiriTerbobot.toFixed(2);
        document.getElementById('penampilan_terbobot').textContent = penampilanTerbobot.toFixed(2);
        document.getElementById('sikap_etika_terbobot').textContent = sikapEtikaTerbobot.toFixed(2);
        document.getElementById('pengetahuan_terbobot').textContent = pengetahuanTerbobot.toFixed(2);

        // Hitung total
        const nilaiTotal = disiplinTerbobot + komunikasiTerbobot + kerjaTimTerbobot + 
                          kerjaMandiriTerbobot + penampilanTerbobot + sikapEtikaTerbobot + 
                          pengetahuanTerbobot;

        // Konversi ke huruf dan skor
        let nilaiHuruf = '-';
        let skor = 0;

        if (nilaiTotal >= 85) { nilaiHuruf = 'A'; skor = 4.0; }
        else if (nilaiTotal >= 80) { nilaiHuruf = 'B+'; skor = 3.5; }
        else if (nilaiTotal >= 75) { nilaiHuruf = 'B'; skor = 3.0; }
        else if (nilaiTotal >= 70) { nilaiHuruf = 'C+'; skor = 2.5; }
        else if (nilaiTotal >= 65) { nilaiHuruf = 'C'; skor = 2.0; }
        else if (nilaiTotal >= 60) { nilaiHuruf = 'D'; skor = 1.0; }
        else if (nilaiTotal > 0) { nilaiHuruf = 'E'; skor = 0.0; }

        // Update tampilan total
        document.getElementById('nilai_total').textContent = nilaiTotal.toFixed(2);
        document.getElementById('nilai_huruf').textContent = nilaiHuruf;
        document.getElementById('skor').textContent = skor.toFixed(2);
    }

    // Hitung saat halaman pertama kali dimuat
    window.addEventListener('DOMContentLoaded', hitungTotal);
</script>

<style>
.nilai-terbobot {
    display: block;
    margin-top: 5px;
    color: #28a745;
    font-weight: 500;
}
</style>

</body>
</html>
