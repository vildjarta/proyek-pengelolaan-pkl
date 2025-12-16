<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Nilai Mahasiswa - Sistem PKL JOZZ</title>
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="margin: 0; font-size: 1.5em;">Tambah Nilai Mahasiswa PKL</h2>
        <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('nilai.store') }}" method="POST" id="nilaiForm">
        @csrf

        <!-- Info Mahasiswa -->
        <div class="form-section" style="padding: 12px; margin-bottom: 12px;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;"><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
            <div id="notifikasi-container" style="display: none; margin-bottom: 10px;"></div>
            <div class="form-row" style="gap: 10px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 3px; font-size: 0.9em;">ID Nilai: <span class="required">*</span></label>
                    <input type="text" name="id_nilai" class="form-control" style="padding: 6px; font-size: 0.9em;" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 3px; font-size: 0.9em;">Pilih Mahasiswa: <span class="required">*</span></label>
                    <select name="id_mahasiswa" id="mahasiswa-select" class="form-control" style="padding: 6px; font-size: 0.9em;" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->nim }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                        @endforeach
                    </select>
                    <small id="loading-indicator" style="display: none; color: #0066cc; font-size: 0.8em;">
                        <i class="fas fa-spinner fa-spin"></i> Mengambil data...
                    </small>
                </div>
            </div>
        </div>

        <!-- Penilaian Pembimbing Lapangan (50%) -->
        <div class="form-section" style="padding: 12px; margin-bottom: 12px;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;"><i class="fas fa-briefcase"></i> Penilaian Pembimbing Lapangan (Bobot 50%)</h3>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Disiplin (0-100):</label>
                    <input type="number" step="0.01" name="disiplin" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Komunikasi (0-100):</label>
                    <input type="number" step="0.01" name="komunikasi" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Kerja Tim (0-100):</label>
                    <input type="number" step="0.01" name="kerja_tim" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-top: 8px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Kerja Mandiri (0-100):</label>
                    <input type="number" step="0.01" name="kerja_mandiri" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Penampilan (0-100):</label>
                    <input type="number" step="0.01" name="penampilan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Sikap/Etika (0-100):</label>
                    <input type="number" step="0.01" name="sikap_etika_lapangan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-top: 8px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Pengetahuan (0-100):</label>
                    <input type="number" step="0.01" name="pengetahuan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-group" style="margin-top: 8px; margin-bottom: 0;">
                <label style="margin-bottom: 2px; font-size: 0.85em;">Catatan:</label>
                <textarea name="catatan_pembimbing" class="form-control" style="padding: 5px; font-size: 0.85em;" rows="2"></textarea>
            </div>
            <div class="subtotal-box" style="padding: 10px; margin-top: 8px; font-size: 0.95em;">
                <strong>Subtotal:</strong> <span id="subtotal_pembimbing">0.00</span>%
            </div>
        </div>

        <!-- Penilaian Dosen Pembimbing (30%) -->
        <div class="form-section" style="padding: 12px; margin-bottom: 12px;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;"><i class="fas fa-chalkboard-teacher"></i> Penilaian Dosen Pembimbing (Bobot 30%)</h3>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Penguasaan Teori (0-100):</label>
                    <input type="number" step="0.01" name="penguasaan_teori" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Kemampuan Analisis (0-100):</label>
                    <input type="number" step="0.01" name="kemampuan_analisis" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Keaktifan Bimbingan (0-100):</label>
                    <input type="number" step="0.01" name="keaktifan_bimbingan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-top: 8px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Kemampuan Penulisan Laporan (0-100):</label>
                    <input type="number" step="0.01" name="kemampuan_penulisan_laporan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Sikap/Etika (0-100):</label>
                    <input type="number" step="0.01" name="sikap_etika_dospem" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-group" style="margin-top: 8px; margin-bottom: 0;">
                <label style="margin-bottom: 2px; font-size: 0.85em;">Catatan:</label>
                <textarea name="catatan_dospem" class="form-control" style="padding: 5px; font-size: 0.85em;" rows="2"></textarea>
            </div>
            <div class="subtotal-box" style="padding: 10px; margin-top: 8px; font-size: 0.95em;">
                <strong>Subtotal:</strong> <span id="subtotal_dospem">0.00</span>%
            </div>
        </div>

        <!-- Penilaian Penguji (20%) -->
        <div class="form-section" style="padding: 12px; margin-bottom: 12px;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;"><i class="fas fa-clipboard-check"></i> Penilaian Penguji (Bobot 20%)</h3>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Penyajian Presentasi (0-100):</label>
                    <input type="number" step="0.01" name="penyajian_presentasi" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Pemahaman Materi (0-100):</label>
                    <input type="number" step="0.01" name="pemahaman_materi" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Hasil yang Dicapai (0-100):</label>
                    <input type="number" step="0.01" name="hasil_yang_dicapai" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-row" style="gap: 10px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-top: 8px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Objektivitas Menanggapi (0-100):</label>
                    <input type="number" step="0.01" name="objektivitas_menangapi" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 2px; font-size: 0.85em;">Penulisan Laporan (0-100):</label>
                    <input type="number" step="0.01" name="penulisan_laporan" class="form-control nilai-input" style="padding: 5px; font-size: 0.85em;" min="0" max="100" value="0" onchange="hitungTotal()">
                </div>
            </div>
            <div class="form-group" style="margin-top: 8px; margin-bottom: 0;">
                <label style="margin-bottom: 2px; font-size: 0.85em;">Catatan:</label>
                <textarea name="catatan_penguji" class="form-control" style="padding: 5px; font-size: 0.85em;" rows="2"></textarea>
            </div>
            <div class="subtotal-box" style="padding: 10px; margin-top: 8px; font-size: 0.95em;">
                <strong>Subtotal:</strong> <span id="subtotal_penguji">0.00</span>%
            </div>
        </div>

        <!-- Total Nilai -->
        <div class="total-nilai-box" style="padding: 15px; margin: 12px 0;">
            <div class="total-row" style="padding: 6px 0; font-size: 0.95em;">
                <strong>NILAI TOTAL:</strong>
                <span id="nilai_total" class="nilai-besar" style="font-size: 1.5em;">0.00</span>
            </div>
            <div class="total-row" style="padding: 6px 0; font-size: 0.95em;">
                <strong>NILAI HURUF:</strong>
                <span id="nilai_huruf" class="nilai-besar" style="font-size: 1.5em;">-</span>
            </div>
            <div class="total-row" style="padding: 6px 0; font-size: 0.95em;">
                <strong>SKOR:</strong>
                <span id="skor" class="nilai-besar" style="font-size: 1.5em;">0.00</span>
            </div>
        </div>

        <div class="form-actions" style="gap: 8px; margin-top: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Nilai
            </button>
            <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
    // Auto-populate nilai saat memilih mahasiswa
    document.getElementById('mahasiswa-select').addEventListener('change', function() {
        const nim = this.value;
        const notifikasiContainer = document.getElementById('notifikasi-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        // Clear previous notifications
        notifikasiContainer.innerHTML = '';
        notifikasiContainer.style.display = 'none';

        if (!nim) return;

        loadingIndicator.style.display = 'inline';

        // Fetch data dari API
        fetch(`/api/nilai/get-penilaian/${nim}`)
            .then(response => response.json())
            .then(result => {
                loadingIndicator.style.display = 'none';

                if (!result.success) {
                    showNotification(result.message, 'error');
                    return;
                }

                const { data } = result;

                // Populate nilai fields
                Object.entries(data.nilai).forEach(([key, value]) => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field) {
                        field.value = value || 0;
                    }
                });

                // Populate catatan
                Object.entries(data.catatan).forEach(([key, value]) => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field && value) {
                        field.value = value;
                    }
                });

                // Show notifications for missing data
                const notifikasi = data.notifikasi;
                const alerts = Object.values(notifikasi).filter(n => n);

                if (alerts.length > 0) {
                    alerts.forEach(alert => {
                        showNotification(alert, 'warning');
                    });
                } else {
                    showNotification('✓ Semua data penilaian ditemukan dan berhasil dimuat!', 'success');
                }

                // Trigger calculation
                hitungTotal();
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                console.error('Error:', error);
                showNotification('Gagal mengambil data penilaian', 'error');
            });
    });

    function showNotification(message, type) {
        const notifikasiContainer = document.getElementById('notifikasi-container');
        notifikasiContainer.style.display = 'block';

        const alertClass = type === 'error' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-success');
        const alertHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        notifikasiContainer.innerHTML += alertHTML;
    }

    function hitungTotal() {
        // Pembimbing Lapangan (nilai 0-100, dikonversi dengan bobot)
        const disiplin = (parseFloat(document.querySelector('[name="disiplin"]').value) || 0) * 0.15;
        const komunikasi = (parseFloat(document.querySelector('[name="komunikasi"]').value) || 0) * 0.10;
        const kerja_tim = (parseFloat(document.querySelector('[name="kerja_tim"]').value) || 0) * 0.15;
        const kerja_mandiri = (parseFloat(document.querySelector('[name="kerja_mandiri"]').value) || 0) * 0.10;
        const penampilan = (parseFloat(document.querySelector('[name="penampilan"]').value) || 0) * 0.10;
        const sikap_etika_lapangan = (parseFloat(document.querySelector('[name="sikap_etika_lapangan"]').value) || 0) * 0.20;
        const pengetahuan = (parseFloat(document.querySelector('[name="pengetahuan"]').value) || 0) * 0.20;

        const subtotalPembimbing = disiplin + komunikasi + kerja_tim + kerja_mandiri + penampilan + sikap_etika_lapangan + pengetahuan;

        // Dosen Pembimbing (nilai 0-100, dikonversi dengan bobot)
        const penguasaan_teori = (parseFloat(document.querySelector('[name="penguasaan_teori"]').value) || 0) * 0.20;
        const kemampuan_analisis = (parseFloat(document.querySelector('[name="kemampuan_analisis"]').value) || 0) * 0.25;
        const keaktifan_bimbingan = (parseFloat(document.querySelector('[name="keaktifan_bimbingan"]').value) || 0) * 0.15;
        const kemampuan_penulisan_laporan = (parseFloat(document.querySelector('[name="kemampuan_penulisan_laporan"]').value) || 0) * 0.20;
        const sikap_etika_dospem = (parseFloat(document.querySelector('[name="sikap_etika_dospem"]').value) || 0) * 0.20;

        const subtotalDospem = penguasaan_teori + kemampuan_analisis + keaktifan_bimbingan + kemampuan_penulisan_laporan + sikap_etika_dospem;

        // Penguji (nilai 0-100, dikonversi dengan bobot)
        const penyajian_presentasi = (parseFloat(document.querySelector('[name="penyajian_presentasi"]').value) || 0) * 0.10;
        const pemahaman_materi = (parseFloat(document.querySelector('[name="pemahaman_materi"]').value) || 0) * 0.15;
        const hasil_yang_dicapai = (parseFloat(document.querySelector('[name="hasil_yang_dicapai"]').value) || 0) * 0.40;
        const objektivitas_menangapi = (parseFloat(document.querySelector('[name="objektivitas_menangapi"]').value) || 0) * 0.20;
        const penulisan_laporan = (parseFloat(document.querySelector('[name="penulisan_laporan"]').value) || 0) * 0.15;

        const subtotalPenguji = penyajian_presentasi + pemahaman_materi + hasil_yang_dicapai + objektivitas_menangapi + penulisan_laporan;

        // Total (subtotal sudah termasuk bobot persentase)
        const nilaiTotal = subtotalPembimbing + subtotalDospem + subtotalPenguji;

        // Konversi nilai huruf
        let nilaiHuruf = '-';
        let skor = 0;

        if (nilaiTotal >= 85) { nilaiHuruf = 'A'; skor = 4.0; }
        else if (nilaiTotal >= 80) { nilaiHuruf = 'B+'; skor = 3.5; }
        else if (nilaiTotal >= 75) { nilaiHuruf = 'B'; skor = 3.0; }
        else if (nilaiTotal >= 70) { nilaiHuruf = 'C+'; skor = 2.5; }
        else if (nilaiTotal >= 65) { nilaiHuruf = 'C'; skor = 2.0; }
        else if (nilaiTotal >= 60) { nilaiHuruf = 'D'; skor = 1.0; }
        else if (nilaiTotal > 0) { nilaiHuruf = 'E'; skor = 0.0; }

        // Update display
        document.getElementById('subtotal_pembimbing').textContent = subtotalPembimbing.toFixed(2);
        document.getElementById('subtotal_dospem').textContent = subtotalDospem.toFixed(2);
        document.getElementById('subtotal_penguji').textContent = subtotalPenguji.toFixed(2);
        document.getElementById('nilai_total').textContent = nilaiTotal.toFixed(2);
        document.getElementById('nilai_huruf').textContent = nilaiHuruf;
        document.getElementById('skor').textContent = skor.toFixed(2);
    }
</script>

</body>
</html>
