<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h2>Cek Kelayakan PKL</h2>
        <p>Paste transkrip kamu di bawah ini (copy dari KHS/Word/PDF).</p>

        <textarea id="pasteArea" class="form-control" rows="8" placeholder="Paste transkrip di sini..."></textarea>
        <br>
        <div id="preview"></div>
        <br>
        <button id="analyzeBtn" class="btn btn-primary">Analisa</button>

        <div id="result" style="margin-top:20px;"></div>
    </div>

    <div id="hasil"></div>
    <form id="saveForm" method="POST" action="/transkrip/save" style="display:none;">
        @csrf
        <input type="hidden" name="nama_mahasiswa" value="Contoh Mahasiswa">
        <input type="hidden" name="nim" value="123456789">
        <input type="hidden" name="ipk" id="ipkInput">
        <input type="hidden" name="total_sks_d" id="sksDInput">
        <input type="hidden" name="has_e" id="hasEInput">
        <input type="hidden" name="eligible" id="eligibleInput">
        <button type="submit">Simpan Hasil</button>
    </form>

    <script>
    const ta = document.getElementById('pasteArea');
    const preview = document.getElementById('preview');

    ta.addEventListener('paste', async (e) => {
    const text = (e.clipboardData || window.clipboardData).getData('text');
    const rows = parseTranscript(text);
    renderTable(rows);
    });

    function parseTranscript(text){
    text = text.replace(/\r\n/g,'\n').trim();
    if (text.includes('\t')) {
        return text.split('\n').map(r => r.split('\t').map(c=>c.trim()));
    }
    if (text.includes(',') && text.split('\n')[0].split(',').length>1) {
        return text.split('\n').map(r => r.split(',').map(c=>c.trim()));
    }
    return text.split('\n').map(r => r.trim()).map(r => r.split(/\s{2,}/).map(c=>c.trim()));
    }

    function renderTable(rows){
    let html = '<table class="table table-bordered"><thead><tr>';
    const header = rows[0];
    for(let h of header) html += `<th contenteditable="true">${h}</th>`;
    html += '</tr></thead><tbody>';
    for(let i=1;i<rows.length;i++){
        html += '<tr>';
        for(let c of rows[i]) html += `<td contenteditable="true">${c}</td>`;
        html += '</tr>';
    }
    html += '</tbody></table>';
    preview.innerHTML = html;
    }

    document.getElementById('analyzeBtn').addEventListener('click', () => {
    const table = preview.querySelector('table');
    if(!table) return alert('Paste dulu transkrip.');
    const arr = Array.from(table.querySelectorAll('tr')).map(tr=>Array.from(tr.children).map(td=>td.innerText.trim()));
    fetch("{{ route('transcript.analyze') }}", {
        method: 'POST',
        headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({table: arr})
        }).then(r=>r.json()).then(data=>{
            if(data.error) {
                document.getElementById('result').innerHTML = `<div class='alert alert-danger'>${data.error}</div>`;
            } else {
                document.getElementById('result').innerHTML = `
                    <div class='alert alert-info'>
                        <strong>IPK:</strong> ${data.ipk ?? '-'} <br>
                        <strong>Total SKS D:</strong> ${data.total_sks_d} <br>
                        <strong>Ada Nilai E:</strong> ${data.has_e ? 'Ya' : 'Tidak'} <br>
                        <strong>Status:</strong> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}
                    </div>
                `;

                // panggil fungsi tampilkanHasil
                tampilkanHasil(data);
            }
        });
    });


    function tampilkanHasil(data) {
    document.getElementById('hasil').innerHTML = `
        <p><b>IPK:</b> ${data.ipk}</p>
        <p><b>Total SKS D:</b> ${data.total_sks_d}</p>
        <p><b>Ada Nilai E:</b> ${data.has_e ? 'Ya' : 'Tidak'}</p>
        <p><b>Status:</b> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}</p>
    `;

    // isi input hidden untuk form simpan
    document.getElementById('ipkInput').value = data.ipk;
    document.getElementById('sksDInput').value = data.total_sks_d;
    document.getElementById('hasEInput').value = data.has_e ? 1 : 0;
    document.getElementById('eligibleInput').value = data.eligible ? 1 : 0;

    document.getElementById('saveForm').style.display = 'block';
    }
    </script>

</body>
</html>
