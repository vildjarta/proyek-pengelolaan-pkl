<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Analisa Transkrip - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
</head>

<body>

    <div class="d-flex">
        {{-- header --}}
        @include('layout.header')
    </div>

    <div class="d-flex">
        {{-- sidebar --}}
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper"> <!-- wadah konten utama -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2>Analisa Transkrip Kelayakan PKL</h2>
                <p>Paste transkrip kamu di bawah ini (copy dari KHS/Word/PDF).</p>
            </div>
            <a href="{{ route('transkrip.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <textarea id="pasteArea" class="form-control" rows="8" placeholder="Paste transkrip di sini..."></textarea> <!-- area paste -->
        <br>
        <div id="preview"></div> <!-- preview tabel hasil paste -->
        <br>
        <button id="analyzeBtn" class="btn btn-primary">Analisa</button> <!-- tombol trigger analisis -->

        <div id="result" style="margin-top:20px;"></div> <!-- hasil analisis ringkas -->

        <form id="saveForm" method="POST" action="/transkrip/save-multiple" style="display:none; margin-top:20px;"> <!-- form hidden simpan ke DB -->
            @csrf <!-- token keamanan Laravel -->
            <div id="transcriptEntries">
                <!-- Dynamic entries will be added here -->
            </div>
            <button type="button" id="addEntryBtn" class="btn btn-secondary" style="margin-bottom: 10px;">
                <i class="fa fa-plus"></i> Tambah Data Mahasiswa
            </button>
            <br>
            <button type="submit" class="btn btn-success">Simpan Semua Data</button> <!-- tombol submit -->
        </form>
    </div>

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

    let entryCounter = 0;
    let analysisResults = [];

    // tombol analisa ditekan
    document.getElementById('analyzeBtn').addEventListener('click', () => {
        const table = preview.querySelector('table'); // ambil tabel hasil paste
        if(!table) return alert('Paste dulu transkrip.');
        const arr = Array.from(table.querySelectorAll('tr')).map(tr=>Array.from(tr.children).map(td=>td.innerText.trim())); // konversi ke array
        fetch("{{ route('transkrip.analyze') }}", { // request ke backend Laravel
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // token keamanan
            },
            body: JSON.stringify({table: arr}) // kirim data tabel
        }).then(r=>r.json()).then(data=>{
            if(data.error) { // jika error parsing
                document.getElementById('result').innerHTML = `<div class='alert alert-danger'>${data.error}</div>`;
                document.getElementById('saveForm').style.display = 'none';
            } else { // tampilkan hasil analisis
                document.getElementById('result').innerHTML = `
                    <div class='alert alert-info'>
                        <strong>IPK:</strong> ${data.ipk ?? '-'} <br>
                        <strong>Total SKS D:</strong> ${data.total_sks_d} <br>
                        <strong>Ada Nilai E:</strong> ${data.has_e ? 'Ya' : 'Tidak'} <br>
                        <strong>Status:</strong> ${data.eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>'}
                    </div>
                `;
                // simpan hasil analisis sementara
                analysisResults = data;
                // tampilkan form simpan
                document.getElementById('saveForm').style.display = 'block';
            }
        });
    });

    // fungsi untuk menambah entry baru
    function addTranscriptEntry(data = null) {
        const container = document.getElementById('transcriptEntries');
        const entryId = entryCounter++;

        const entryDiv = document.createElement('div');
        entryDiv.className = 'transcript-entry';
        entryDiv.style.cssText = 'border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background: #f9f9f9;';
        entryDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h4 style="margin: 0;">Data Mahasiswa #${entryId + 1}</h4>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(${entryId})">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <label>Nama Mahasiswa:</label>
                    <input type="text" name="entries[${entryId}][nama_mahasiswa]" class="form-control" value="${data?.nama_mahasiswa || ''}" required>
                </div>
                <div>
                    <label>NIM:</label>
                    <input type="text" name="entries[${entryId}][nim]" class="form-control" value="${data?.nim || ''}" required>
                </div>
                <div>
                    <label>IPK:</label>
                    <input type="number" step="0.01" name="entries[${entryId}][ipk]" class="form-control" value="${data?.ipk || analysisResults.ipk || ''}" required>
                </div>
                <div>
                    <label>Total SKS D:</label>
                    <input type="number" name="entries[${entryId}][total_sks_d]" class="form-control" value="${data?.total_sks_d || analysisResults.total_sks_d || 0}" required>
                </div>
                <div>
                    <label>Ada Nilai E:</label>
                    <select name="entries[${entryId}][has_e]" class="form-control" required>
                        <option value="0" ${(data?.has_e === false || analysisResults.has_e === false) ? 'selected' : ''}>Tidak</option>
                        <option value="1" ${(data?.has_e === true || analysisResults.has_e === true) ? 'selected' : ''}>Ya</option>
                    </select>
                </div>
                <div>
                    <label>Status Kelayakan:</label>
                    <select name="entries[${entryId}][eligible]" class="form-control" required>
                        <option value="0" ${(data?.eligible === false || analysisResults.eligible === false) ? 'selected' : ''}>Tidak Layak</option>
                        <option value="1" ${(data?.eligible === true || analysisResults.eligible === true) ? 'selected' : ''}>Layak</option>
                    </select>
                </div>
            </div>
        `;

        container.appendChild(entryDiv);
    }

    // fungsi untuk menghapus entry
    window.removeEntry = function(entryId) {
        const entries = document.querySelectorAll('.transcript-entry');
        if (entries.length > 1) {
            entries[entryId].remove();
        } else {
            alert('Minimal harus ada satu data mahasiswa.');
        }
    };

    // tombol tambah entry
    document.getElementById('addEntryBtn').addEventListener('click', () => {
        addTranscriptEntry();
    });

    // auto-add first entry when form is shown
    const saveForm = document.getElementById('saveForm');
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'style' && saveForm.style.display === 'block') {
                if (document.getElementById('transcriptEntries').children.length === 0) {
                    addTranscriptEntry();
                }
            }
        });
    });
    observer.observe(saveForm, { attributes: true });
    </script>

    <script>
        // Script untuk toggle sidebar dan user menu dropdown
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
