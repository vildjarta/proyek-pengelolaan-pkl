<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Dosen Pembimbing | Sistem PKL JOZZ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/tambahdatadosenpembimbing.css') }}">
</head>
<body>

  @include('layout.header')
  @include('layout.sidebar')

  <div class="main-wrapper" id="main-wrapper">
    <div class="content-container container-fluid px-4">
      <div class="content-card shadow-sm w-100 p-4">
        <h3 class="text-center mb-4 page-header">Tambah Dosen Pembimbing</h3>
        <hr>

        <form action="{{ route('datadosenpembimbing.store') }}" method="POST" id="formDosenPembimbing">
          @csrf

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- NIP & Dosen -->
          <div class="mb-3 position-relative" style="min-height:70px;">
            <label class="form-label required">NIP</label>
            <input type="text" name="NIP" id="NIP" class="form-control" maxlength="18"
                   placeholder="Masukkan 18 digit NIP" required value="{{ old('NIP') }}" autocomplete="off" inputmode="numeric">
            <div class="suggest-wrap" id="suggest-nip" style="display:none"></div>
            <div id="nipError" class="text-danger mt-1" style="display:none;">NIP harus 18 angka.</div>
          </div>

          <div class="mb-3">
            <label class="form-label required">Nama Dosen</label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Terisi Otomatis" readonly value="{{ old('nama') }}">
          </div>

          <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email Terisi Otomatis" readonly value="{{ old('email') }}">
          </div>

          <div class="mb-3">
            <label class="form-label required">No. HP</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control" maxlength="13" placeholder="No. HP Terisi Otomatis" required value="{{ old('no_hp') }}" inputmode="numeric" readonly>
          </div>

          <!-- Daftar Mahasiswa -->
          <div class="mb-3">
            <h5 class="fw-bold text-primary mb-3">Daftar Mahasiswa Bimbingan</h5>
            <div id="mahasiswa-list">
              <div class="mahasiswa-item mb-3 p-3 border rounded position-relative" style="min-height:120px;">
                <button type="button" class="btn btn-danger btn-sm remove-mahasiswa position-absolute top-0 end-0 mt-2 me-2" style="display:none;">
                  <i class="bi bi-trash"></i> Hapus
                </button>

                <div class="mb-3 mt-3 position-relative">
                  <label class="form-label required">NIM Mahasiswa</label>
                  <input type="text" name="nim[]" class="form-control nimInput" placeholder="Masukkan NIM Mahasiswa" autocomplete="off" required maxlength="12" inputmode="numeric">
                  <div class="suggest-wrap nim-suggest" style="display:none"></div>
                  <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">NIM tidak ditemukan.</div>
                  <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
                    NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold"></span>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label required">Nama Mahasiswa</label>
                  <input type="text" name="nama_mahasiswa[]" class="form-control namaInput" placeholder="Nama Mahasiswa Terisi Otomatis" readonly required>
                </div>
              </div>
            </div>

            <button type="button" id="add-mahasiswa" class="btn btn-primary btn-sm mt-2">
              <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </button>
          </div>

          <div class="text-center mt-4">
            <button type="submit" id="btnSubmit" class="btn btn-success px-4 me-2">
              <i class="bi bi-check-circle"></i> Simpan
            </button>
            <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary px-4">
              <i class="bi bi-x-circle"></i> Batal
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* helper debounce */
function debounce(fn, wait=220){ let t; return function(...a){ clearTimeout(t); t=setTimeout(()=>fn.apply(this,a), wait); }; }

/* base URLs (jalankan dari blade agar base path benar) */
const urlCekDosenSuggest = "{{ url('cek-dosen-suggest') }}";
const urlCekDosenByNip = "{{ url('cek-dosen') }}"; // will append /{nip}
const urlCekNimSuggest = "{{ url('cek-nim-suggest') }}";
const urlCekNim = "{{ url('cek-nim') }}"; // will append /{nim}

/* small helper headers */
function baseFetchHeaders() {
  return {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
  };
}

/* NIP elements */
const nipInput = document.getElementById('NIP');
const suggestNip = document.getElementById('suggest-nip');
const nipError = document.getElementById('nipError');
const namaField = document.getElementById('nama');
const emailField = document.getElementById('email');
const noHpField = document.getElementById('no_hp');

function updateNipState(value) {
  const len = (value||'').length;
  if (!nipInput) return;
  if (len === 0) {
    nipError.style.display = 'none';
    nipInput.classList.remove('is-invalid','is-valid');
  } else if (len < 18) {
    nipError.style.display = 'block';
    nipInput.classList.add('is-invalid');
    nipInput.classList.remove('is-valid');
  } else {
    nipError.style.display = 'none';
    nipInput.classList.remove('is-invalid');
    nipInput.classList.add('is-valid');
  }
}

/* NIP suggest */
if (nipInput && suggestNip) {
  nipInput.addEventListener('input', debounce(async function(){
    const q = this.value.trim();
    if (!q) { suggestNip.style.display='none'; updateNipState(q); return; }
    try {
      const res = await fetch(`${urlCekDosenSuggest}?q=${encodeURIComponent(q)}`, { headers: baseFetchHeaders(), credentials:'same-origin' });
      if (!res.ok) { suggestNip.style.display='none'; return; }
      const list = await res.json();
      suggestNip.innerHTML = '';
      if (Array.isArray(list) && list.length) {
        list.forEach(r=>{
          const el = document.createElement('div');
          el.className = 'item';
          el.dataset.nip = r.nip ?? r.NIP ?? '';
          el.dataset.nama = r.nama ?? '';
          el.dataset.email = r.email ?? '';
          el.dataset.no_hp = r.no_hp ?? '';
          el.innerHTML = `<strong>${el.dataset.nip}</strong> — <small>${el.dataset.nama}</small>`;
          el.addEventListener('mousedown', function(e){
            e.preventDefault();
            nipInput.value = this.dataset.nip;
            namaField.value = this.dataset.nama;
            emailField.value = this.dataset.email;
            noHpField.value = this.dataset.no_hp;
            suggestNip.style.display = 'none';
            updateNipState(nipInput.value);
          });
          suggestNip.appendChild(el);
        });
        suggestNip.style.display = 'block';
      } else {
        suggestNip.style.display = 'none';
      }
    } catch(e){ console.error(e); suggestNip.style.display = 'none'; }
  }, 160));
  // blur lookup
  nipInput.addEventListener('blur', function(){
    setTimeout(async ()=> {
      const v = this.value.trim();
      updateNipState(v);
      if (!v) { suggestNip.style.display='none'; return; }
      try {
        const res = await fetch(`${urlCekDosenByNip}/${encodeURIComponent(v)}`, { headers: baseFetchHeaders(), credentials:'same-origin' });
        if (res.ok) {
          const data = await res.json();
          if (data && data.exists) {
            namaField.value = data.nama ?? '';
            emailField.value = data.email ?? '';
            noHpField.value = data.no_hp ?? '';
          }
        }
      } catch(e){}
      suggestNip.style.display='none';
    }, 150);
  });
}

/* Mahasiswa autocomplete setup */
function allowDigitsOnly(el) {
  el.addEventListener('keypress', function(e){
    const char = String.fromCharCode(e.which || e.keyCode);
    if (!/[0-9]/.test(char)) e.preventDefault();
  });
  el.addEventListener('input', function(){
    this.value = this.value.replace(/\D/g,'').slice(0,12);
  });
}

function setupMahasiswaItem(item, isClone=false) {
  const nimInput = item.querySelector('.nimInput');
  const suggestWrap = item.querySelector('.nim-suggest');
  const nimError = item.querySelector('.nimError');
  const nimSuccess = item.querySelector('.nimSuccess');
  const namaTampil = item.querySelector('.namaTampil');
  const namaInput = item.querySelector('.namaInput');
  const btnRemove = item.querySelector('.remove-mahasiswa');

  if (!nimInput || !suggestWrap) return;
  if (isClone && btnRemove) btnRemove.style.display = 'block';

  allowDigitsOnly(nimInput);

  const doSuggest = debounce(async function(){
    const q = this.value.trim();
    if (!q) { suggestWrap.style.display='none'; return; }
    try {
      const res = await fetch(`${urlCekNimSuggest}?q=${encodeURIComponent(q)}`, { headers: baseFetchHeaders(), credentials:'same-origin' });
      if (!res.ok) { suggestWrap.style.display='none'; return; }
      const list = await res.json();
      suggestWrap.innerHTML = '';
      if (Array.isArray(list) && list.length) {
        list.forEach(r=>{
          const el = document.createElement('div');
          el.className = 'item';
          el.dataset.nim = r.nim ?? r.NIM ?? '';
          el.dataset.nama = r.nama ?? r.nama_mahasiswa ?? '';
          el.innerHTML = `<strong>${el.dataset.nim}</strong> — <small>${el.dataset.nama}</small>`;
          el.addEventListener('mousedown', function(e){
            e.preventDefault();
            nimInput.value = this.dataset.nim;
            if (namaInput) namaInput.value = this.dataset.nama;
            if (namaTampil) namaTampil.textContent = this.dataset.nama;
            nimError.style.display='none';
            nimSuccess.style.display='block';
            suggestWrap.style.display='none';
          });
          suggestWrap.appendChild(el);
        });
        // ensure position inside wrapper
        suggestWrap.style.display = 'block';
        suggestWrap.style.zIndex = 99999;
      } else {
        suggestWrap.style.display = 'none';
      }
    } catch(err){ console.error(err); suggestWrap.style.display='none'; }
  }, 200);

  nimInput.addEventListener('input', doSuggest);

  nimInput.addEventListener('blur', function(){
    setTimeout(async ()=> {
      const v = this.value.trim();
      if (!v) {
        if (namaTampil) namaTampil.textContent='';
        if (namaInput) namaInput.value='';
        nimError.style.display='none';
        nimSuccess.style.display='none';
        suggestWrap.style.display='none';
        return;
      }
      try {
        const res = await fetch(`${urlCekNim}/${encodeURIComponent(v)}`, { headers: baseFetchHeaders(), credentials:'same-origin' });
        if (!res.ok) throw 0;
        const data = await res.json();
        if (data && data.exists) {
          nimError.style.display='none';
          nimSuccess.style.display='block';
          if (namaTampil) namaTampil.textContent = data.nama_mahasiswa ?? '';
          if (namaInput) namaInput.value = data.nama_mahasiswa ?? '';
        } else {
          nimError.style.display='block';
          nimSuccess.style.display='none';
          if (namaTampil) namaTampil.textContent = '';
          if (namaInput) namaInput.value = '';
        }
      } catch(e){
        nimError.style.display='block';
        nimSuccess.style.display='none';
      }
      suggestWrap.style.display='none';
    },150);
  });

  if (btnRemove) btnRemove.addEventListener('click', ()=> item.remove());
}

/* init existing items */
document.querySelectorAll('.mahasiswa-item').forEach(it=> setupMahasiswaItem(it, false));

/* add new */
document.getElementById('add-mahasiswa').addEventListener('click', function(){
  const list = document.getElementById('mahasiswa-list');
  const first = list.firstElementChild;
  const clone = first.cloneNode(true);
  clone.querySelectorAll('input').forEach(inp=>{
    inp.value = '';
    inp.classList.remove('is-valid','is-invalid');
  });
  const err = clone.querySelector('.nimError'); if (err) err.style.display='none';
  const ok = clone.querySelector('.nimSuccess'); if (ok) ok.style.display='none';
  const suggest = clone.querySelector('.nim-suggest'); if (suggest) suggest.style.display='none';
  const btn = clone.querySelector('.remove-mahasiswa'); if (btn) btn.style.display = 'block';
  setupMahasiswaItem(clone, true);
  list.appendChild(clone);
});
</script>

</body>
</html>
