@extends('layout.header')

@section('content')
@include('layout.sidebar')

<div class="main-content-wrapper">
    <div class="container" style="max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2><i class="fa fa-file-pdf"></i> Analisa PDF Transkrip</h2>
        <p>Upload file PDF transkrip dari SIPADU untuk melihat kelayakan PKL.</p>

        <form id="pdfForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="nim" style="display: block; margin-bottom: 5px; font-weight: bold;">NIM Mahasiswa:</label>
                <input type="text" name="nim" id="nim" required placeholder="Masukkan NIM" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="pdf" style="display: block; margin-bottom: 5px; font-weight: bold;">File PDF Transkrip:</label>
                <input type="file" name="pdf" accept=".pdf" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
            </div>
            <br>
            <button type="submit" class="btn" style="background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer;">Analisa PDF</button>
        </form>

        <div id="result" class="result" style="margin-top: 20px; padding: 15px; border-radius: 10px;"></div>
    </div>
</div>
        padding: 15px;
        border-radius: 10px;
    }
    .btn {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn:hover {
        background: #0056b3;
    }
</style>

<script>
document.getElementById('pdfForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = '<p>Memproses PDF...</p>';

    const formData = new FormData(this);
    try {
        const res = await fetch("{{ route('transkrip.uploadPdf') }}", {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (!res.ok) {
            resultDiv.innerHTML = '<span style="color:red;">Error: ' + (data.error || 'Terjadi kesalahan') + '</span>';
            return;
        }

        if (data.error) {
            resultDiv.innerHTML = '<span style="color:red;">' + data.error + '</span>';
        } else if (data.ipk !== undefined && data.ipk !== null) {
            resultDiv.innerHTML = `
                <div style="background: #d4edda; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb;">
                    <h4 style="margin-top: 0;">Hasil Analisa PDF</h4>
                    <p><b>IPK:</b> ${data.ipk}</p>
                    <p><b>Total SKS D:</b> ${data.total_sks_d}</p>
                    <p><b>Ada Nilai E:</b> ${data.has_e ? 'Ya' : 'Tidak'}</p>
                    <p><b>Status:</b> ${data.eligible ? '<span style="color:green; font-weight:bold;">✓ Layak PKL</span>' : '<span style="color:red; font-weight:bold;">✗ Tidak Layak PKL</span>'}</p>
                </div>
            `;
        } else {
            resultDiv.innerHTML = '<span style="color:orange;">⚠ Tidak ada data terdeteksi dari PDF. Pastikan format PDF sesuai dengan transkrip SIPADU.</span>';
        }
    } catch (error) {
        resultDiv.innerHTML = '<span style="color:red;">Error: ' + error.message + '</span>';
        console.error('Error:', error);
    }
});
</script>
@endsection
