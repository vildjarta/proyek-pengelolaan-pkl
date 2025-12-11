<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisa PDF Transkrip - Sistem PKL JOZZ</title>
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
    <div class="container" style="max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); margin-bottom: 30px;">

        <!-- Header -->
        <div style="display: flex; align-items: center; margin-bottom: 30px; gap: 15px;">
            <i class="fas fa-file-pdf" style="font-size: 2.5em; color: #dc3545;"></i>
            <div>
                <h1 style="margin: 0; color: #333;">Analisa PDF Transkrip</h1>
                <p style="margin: 5px 0 0 0; color: #666;">Upload file PDF transkrip dari SIPADU untuk melihat kelayakan PKL</p>
            </div>
        </div>

        <!-- Tutorial Card -->
        <div class="tutorial-card">
            <h3><i class="fas fa-lightbulb"></i> Cara Menggunakan</h3>
            <ol>
                <li><strong>Masukkan NIM</strong> - Pastikan NIM sudah terdaftar di sistem</li>
                <li><strong>Pilih File PDF</strong> - Upload transkrip dari SIPADU (format PDF)</li>
                <li><strong>Klik Analisa PDF</strong> - Sistem akan membaca dan menganalisis data</li>
                <li><strong>Lihat Hasil</strong> - Data otomatis tersimpan jika berhasil dianalisis</li>
            </ol>
            <div class="tips-box">
                <strong>💡 Tips:</strong> Pastikan PDF yang diupload adalah transkrip resmi dari SIPADU dengan format standar. Jika hasil tidak terdeteksi, gunakan fitur "Tambah Data" untuk input manual.
            </div>
        </div>

        <!-- Form Upload -->
        <form id="pdfForm" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="nim" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        <i class="fas fa-id-card"></i> NIM Mahasiswa <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="nim" id="nim" required placeholder="Contoh: 2021-08-001"
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 1em;"
                        onchange="this.style.borderColor = this.value ? '#28a745' : '#ddd';">
                    <small style="color: #666; display: block; margin-top: 5px;">Masukkan NIM tanpa spasi</small>
                </div>

                <div class="form-group">
                    <label for="pdf" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        <i class="fas fa-file-upload"></i> File PDF Transkrip <span style="color: red;">*</span>
                    </label>
                    <input type="file" name="pdf" id="pdf" accept=".pdf" required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; cursor: pointer;"
                        onchange="this.style.borderColor = this.files.length > 0 ? '#28a745' : '#ddd';">
                    <small style="color: #666; display: block; margin-top: 5px;">Format: PDF | Ukuran maksimal: 2MB</small>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button type="submit" id="analyzeBtn" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-search"></i> Analisa PDF
                </button>
                <button type="reset" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; background: #6c757d;">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <a href="{{ route('transkrip.index') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; border: 1px solid #007bff; color: #007bff; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Result Area -->
            <div id="result" style="display: none; margin-top: 20px; padding: 20px; border-radius: 8px; border-left: 5px solid #007bff;"></div>
        </form>

    </div>
</div>

<style>
    .main-content-wrapper {
        margin-left: 250px;
        padding: 20px;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .container {
        max-width: 100%;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1em;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 86, 179, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 2px solid #007bff;
        color: #007bff;
    }

    .btn-outline:hover {
        background: #f0f7ff;
    }

    .btn:active {
        transform: translateY(0);
    }

    #analyzeBtn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 768px) {
        .main-content-wrapper {
            margin-left: 0;
            padding: 10px;
        }

        .container {
            padding: 15px !important;
        }

        .tutorial-card {
            padding: 15px;
        }

        [style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    const pdfForm = document.getElementById('pdfForm');
    const resultDiv = document.getElementById('result');
    const analyzeBtn = document.getElementById('analyzeBtn');

    pdfForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const nim = document.getElementById('nim').value.trim();
        const pdfFile = document.getElementById('pdf').files[0];

        if (!nim || !pdfFile) {
            resultDiv.style.display = 'block';
            resultDiv.style.borderLeftColor = '#dc3545';
            resultDiv.innerHTML = '<span style="color: #dc3545;"><i class="fas fa-exclamation-circle"></i> Harap isi semua field terlebih dahulu</span>';
            return;
        }

        // Show loading state
        analyzeBtn.disabled = true;
        analyzeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang memproses...';
        resultDiv.style.display = 'block';
        resultDiv.style.borderLeftColor = '#17a2b8';
        resultDiv.innerHTML = '<p style="color: #17a2b8;"><i class="fas fa-hourglass-half"></i> Memproses PDF... Mohon tunggu...</p>';

        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('nim', nim);
        formData.append('pdf', pdfFile);

        try {
            const res = await fetch("{{ route('transkrip.uploadPdf') }}", {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<i class="fas fa-search"></i> Analisa PDF';

            if (!res.ok) {
                resultDiv.style.borderLeftColor = '#dc3545';
                resultDiv.innerHTML = '<div style="background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;"><i class="fas fa-times-circle"></i> <strong>Error:</strong> ' + (data.error || 'Terjadi kesalahan saat memproses PDF') + '</div>';
                return;
            }

            if (data.error) {
                resultDiv.style.borderLeftColor = '#dc3545';
                resultDiv.innerHTML = '<div style="background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;"><i class="fas fa-times-circle"></i> ' + data.error + '</div>';
            } else if (data.ipk !== undefined && data.ipk !== null) {
                const statusColor = data.eligible ? '#28a745' : '#dc3545';
                const statusText = data.eligible ? '<i class="fas fa-check-circle"></i> ✓ LAYAK PKL' : '<i class="fas fa-times-circle"></i> ✗ TIDAK LAYAK PKL';
                const bgColor = data.eligible ? '#d4edda' : '#f8d7da';
                const borderColor = data.eligible ? '#28a745' : '#dc3545';

                resultDiv.style.borderLeftColor = borderColor;
                resultDiv.innerHTML = `
                    <div style="background: ${bgColor}; padding: 20px; border-radius: 8px; border: 1px solid ${borderColor};">
                        <h4 style="margin-top: 0; color: ${statusColor}; display: flex; align-items: center; gap: 8px;">
                            ${statusText}
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                            <div style="background: white; padding: 12px; border-radius: 5px; border-left: 3px solid #007bff;">
                                <strong style="color: #555; font-size: 0.9em;">IPK</strong>
                                <p style="margin: 5px 0 0 0; font-size: 1.5em; font-weight: bold; color: #007bff;">${parseFloat(data.ipk).toFixed(2)}</p>
                            </div>
                            <div style="background: white; padding: 12px; border-radius: 5px; border-left: 3px solid #007bff;">
                                <strong style="color: #555; font-size: 0.9em;">Total SKS D</strong>
                                <p style="margin: 5px 0 0 0; font-size: 1.5em; font-weight: bold; color: #007bff;">${data.total_sks_d}</p>
                            </div>
                            <div style="background: white; padding: 12px; border-radius: 5px; border-left: 3px solid #ffc107;">
                                <strong style="color: #555; font-size: 0.9em;">Ada Nilai E</strong>
                                <p style="margin: 5px 0 0 0; font-size: 1.2em; font-weight: bold; color: ${data.has_e ? '#dc3545' : '#28a745'};">${data.has_e ? '<i class="fas fa-times"></i> Ya' : '<i class="fas fa-check"></i> Tidak'}</p>
                            </div>
                            <div style="background: white; padding: 12px; border-radius: 5px; border-left: 3px solid ${statusColor};">
                                <strong style="color: #555; font-size: 0.9em;">Status Kelayakan</strong>
                                <p style="margin: 5px 0 0 0; font-size: 1.2em; font-weight: bold; color: ${statusColor};">${data.eligible ? 'LAYAK' : 'TIDAK LAYAK'}</p>
                            </div>
                        </div>
                        <div style="background: #e8f4f8; padding: 12px; border-radius: 5px; margin-top: 15px; border-left: 3px solid #17a2b8;">
                            <small style="color: #555;">✓ Data telah disimpan ke database secara otomatis</small>
                        </div>
                    </div>
                `;
            } else {
                resultDiv.style.borderLeftColor = '#ffc107';
                resultDiv.innerHTML = '<div style="background: #fff3cd; padding: 15px; border-radius: 5px; color: #856404;"><i class="fas fa-exclamation-triangle"></i> <strong>Peringatan:</strong> Tidak ada data terdeteksi dari PDF. Pastikan format PDF sesuai dengan transkrip SIPADU. Anda dapat menggunakan fitur "Tambah Data" untuk input manual.</div>';
            }
        } catch (error) {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<i class="fas fa-search"></i> Analisa PDF';
            resultDiv.style.borderLeftColor = '#dc3545';
            resultDiv.innerHTML = '<div style="background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;"><i class="fas fa-times-circle"></i> <strong>Error Network:</strong> ' + error.message + '</div>';
            console.error('Error:', error);
        }
    });
</script>

</body>
</html>
