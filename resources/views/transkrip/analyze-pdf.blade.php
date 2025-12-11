@extends('layout.header')

@section('content')
@include('layout.sidebar')

<div class="main-content">
    <div class="container">
        <h2><i class="fa fa-file-pdf"></i> Analisa PDF Transkrip</h2>
        <p>Upload file PDF transkrip dari SIPADU untuk melihat kelayakan PKL.</p>

        <form id="pdfForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nim">NIM Mahasiswa:</label>
                <input type="text" name="nim" id="nim" required placeholder="Masukkan NIM">
            </div>
            <div class="form-group">
                <label for="pdf">File PDF Transkrip:</label>
                <input type="file" name="pdf" accept=".pdf" required>
            </div>
            <br>
            <button type="submit" class="btn">Analisa PDF</button>
        </form>

        <div id="result" class="result"></div>
    </div>
</div>

<style>
    .main-content {
        margin-left: 250px; /* Adjust based on sidebar width */
        padding: 20px;
        background: #fafafa;
        min-height: 100vh;
    }
    .container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }
    .result {
        margin-top: 20px;
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

    const formData = new FormData(this);
    const res = await fetch("{{ route('transkrip.uploadPdf') }}", {
        method: 'POST',
        body: formData
    });

    const data = await res.json();
    if (data.error) {
        document.getElementById('result').innerHTML = '<span style="color:red;">' + data.error + '</span>';
    } else if (data.ipk !== undefined) {
        document.getElementById('result').innerHTML = `
            <b>IPK:</b> ${data.ipk}<br>
            <b>Total SKS D:</b> ${data.total_sks_d}<br>
            <b>Ada Nilai E:</b> ${data.has_e ? 'Ya' : 'Tidak'}<br>
            <b>Status:</b> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}
        `;
    } else {
        document.getElementById('result').innerHTML = '<span style="color:red;">Gagal membaca PDF. Pastikan format sesuai.</span>';
    }
});
</script>
@endsection
