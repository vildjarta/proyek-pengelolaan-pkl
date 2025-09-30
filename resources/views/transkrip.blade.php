<html lang="en"> <!-- oke, bahasa dokumen -->
<head>
    <meta charset="UTF-8"> <!-- biar support karakter latin, unicode -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- responsive design -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> <!-- untuk IE compatibility, rarely useful now -->
    <title>Transcript</title> <!-- judul tab browser -->
</head>
<body>
    @extends('layout.header') <!-- include header layout -->
    @extends('layout.sidebar') <!-- include sidebar layout -->

    <div class="main-content-wrapper"> <!-- wadah konten utama -->
        <h2>Cek Kelayakan PKL</h2> <!-- judul halaman -->
        <p>Paste transkrip kamu di bawah ini (copy dari KHS/Word/PDF).</p> <!-- instruksi -->
    <div class="container">
        <h2>Cek Kelayakan PKL</h2>
        <p>Paste transkrip kamu di bawah ini (copy dari KHS/Word/PDF).</p>

        <textarea id="pasteArea" class="form-control" rows="8" placeholder="Paste transkrip di sini..."></textarea> <!-- area paste -->
        <br>
        <div id="preview"></div> <!-- preview tabel hasil paste -->
        <br>
        <button id="analyzeBtn" class="btn btn-primary">Analisa</button> <!-- tombol trigger analisis -->

        <div id="result" style="margin-top:20px;"></div> <!-- hasil analisis ringkas -->
    </div>

    <div id="hasil" class="main-content-wrapper"></div> <!-- hasil detail + status -->
    <form id="saveForm" method="POST" action="/transkrip/save" style="display:none;"> <!-- form hidden simpan ke DB -->
        @csrf <!-- token keamanan Laravel -->
        <input type="hidden" name="nama_mahasiswa" value="Contoh Mahasiswa"> <!-- sementara statis -->
        <input type="hidden" name="nim" value="123456789"> <!-- sementara statis -->
        <input type="hidden" name="ipk" id="ipkInput"> <!-- nilai IPK hasil analisis -->
        <input type="hidden" name="total_sks_d" id="sksDInput"> <!-- jumlah SKS D -->
        <input type="hidden" name="has_e" id="hasEInput"> <!-- flag ada nilai E -->
        <input type="hidden" name="eligible" id="eligibleInput"> <!-- hasil layak/tidak -->
        <button type="submit">Simpan Hasil</button> <!-- tombol submit -->
    </form>

    <script>
    const ta = document.getElementById('pasteArea'); // textarea input
    const preview = document.getElementById('preview'); // preview tabel

    // saat user paste teks transkrip
    ta.addEventListener('paste', async (e) => {
        const text = (e.clipboardData || window.clipboardData).getData('text'); // ambil isi clipboard
        const rows = parseTranscript(text); // parsing ke array 2D
        renderTable(rows); // render jadi tabel HTML
    });

    // fungsi parsing teks → array
    function parseTranscript(text){
        text = text.replace(/\r\n/g,'\n').trim(); // normalisasi newline
        if (text.includes('\t')) { // jika formatnya tab
            return text.split('\n').map(r => r.split('\t').map(c=>c.trim()));
        }
        if (text.includes(',') && text.split('\n')[0].split(',').length>1) { // jika CSV
            return text.split('\n').map(r => r.split(',').map(c=>c.trim()));
        }
        return text.split('\n').map(r => r.trim()).map(r => r.split(/\s{2,}/).map(c=>c.trim())); // fallback: spasi ganda
    }

    // render array → tabel HTML
    function renderTable(rows){
        let html = '<table class="table table-bordered"><thead><tr>';
        const header = rows[0]; // ambil baris header
        for(let h of header) html += `<th contenteditable="true">${h}</th>`; // kolom bisa diedit
        html += '</tr></thead><tbody>';
        for(let i=1;i<rows.length;i++){ // isi baris data
            html += '<tr>';
            for(let c of rows[i]) html += `<td contenteditable="true">${c}</td>`;
            html += '</tr>';
        }
        html += '</tbody></table>';
        preview.innerHTML = html; // tampilkan
    }

    // tombol analisa ditekan
    document.getElementById('analyzeBtn').addEventListener('click', () => {
        const table = preview.querySelector('table'); // ambil tabel hasil paste
        if(!table) return alert('Paste dulu transkrip.');
        const arr = Array.from(table.querySelectorAll('tr')).map(tr=>Array.from(tr.children).map(td=>td.innerText.trim())); // konversi ke array
        fetch("{{ route('transcript.analyze') }}", { // request ke backend Laravel
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // token keamanan
            },
            body: JSON.stringify({table: arr}) // kirim data tabel
        }).then(r=>r.json()).then(data=>{
            if(data.error) { // jika error parsing
                document.getElementById('result').innerHTML = `<div class='alert alert-danger'>${data.error}</div>`;
            } else { // tampilkan hasil analisis
                document.getElementById('result').innerHTML = `
                    <div class='alert alert-info'>
                        <strong>IPK:</strong> ${data.ipk ?? '-'} <br>
                        <strong>Total SKS D:</strong> ${data.total_sks_d} <br>
                        <strong>Ada Nilai E:</strong> ${data.has_e ? 'Ya' : 'Tidak'} <br>
                        <strong>Status:</strong> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}
                    </div>
                `;
                // panggil fungsi untuk tampilkan detail + form
                tampilkanHasil(data);
            }
        });
    });

    // tampilkan detail hasil + isi form hidden
    function tampilkanHasil(data) {
        document.getElementById('hasil').innerHTML = `
            <p><b>IPK:</b> ${data.ipk}</p>
            <p><b>Total SKS D:</b> ${data.total_sks_d}</p>
            <p><b>Ada Nilai E:</b> ${data.has_e ? 'Ya' : 'Tidak'}</p>
            <p><b>Status:</b> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}</p>
        `;

        // isi hidden input agar bisa disimpan
        document.getElementById('ipkInput').value = data.ipk;
        document.getElementById('sksDInput').value = data.total_sks_d;
        document.getElementById('hasEInput').value = data.has_e ? 1 : 0;
        document.getElementById('eligibleInput').value = data.eligible ? 1 : 0;

        document.getElementById('saveForm').style.display = 'block'; // munculkan tombol simpan
    }
    </script>

</body>
</html>
