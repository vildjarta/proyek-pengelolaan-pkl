<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Dosen - PKL JOZZ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/editdatadosen.css') }}">
</head>
<body>

  @include('layout.header')
  @include('layout.sidebar')

  <div class="main-wrapper" id="mainWrapper">
    <div class="content-container container-fluid px-4">
      <div class="content-card shadow-sm w-100">

        <h2 class="text-center mb-4 page-header">Edit Dosen</h2>
        <hr>

        <form action="{{ route('dosen.update', $dosen->id) }}" method="POST" id="formEditDosen" class="needs-validation" novalidate>
          @csrf @method('PUT')

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label required">NIP</label>
              <input type="text" name="nip" id="nip" class="form-control" maxlength="18" inputmode="numeric"
                     placeholder="Masukkan 18 digit NIP" required value="{{ old('nip', $dosen->nip) }}">
              <!-- invalid-feedback untuk NIP; teks akan diatur via JS sesuai kondisi -->
              <div id="nipInvalid" class="invalid-feedback">NIP wajib diisi.</div>
            </div>

            <div class="col-12">
              <label class="form-label required">Nama Dosen</label>
              <input type="text" name="nama" class="form-control" placeholder="Masukkan nama dosen" required value="{{ old('nama', $dosen->nama) }}">
              <div class="invalid-feedback">Nama dosen wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label required">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" required value="{{ old('email', $dosen->email) }}">
              <!-- ubah teks menjadi Email Wajib diisi -->
              <div id="emailInvalid" class="invalid-feedback">Email Wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label required">Nomor HP</label>
              <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" maxlength="13" inputmode="numeric"
                     placeholder="08xxxxxxxxxxx" required value="{{ old('nomor_hp', $dosen->nomor_hp) }}">
              <div class="invalid-feedback">Nomor HP wajib diisi.</div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn action-accept px-4 me-2">
              <span class="icon-circle"><i class="bi bi-check-lg"></i></span>
              Perbarui
            </button>

            <a href="{{ route('dosen.index') }}" class="btn action-cancel px-4">
              <span class="icon-circle"><i class="bi bi-x-lg"></i></span>
              Batal
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const nipEl = document.getElementById('nip');
    const nipInvalid = document.getElementById('nipInvalid');
    const hpEl = document.getElementById('nomor_hp');
    const form = document.getElementById('formEditDosen');
    const emailEl = document.getElementById('email');
    const emailInvalid = document.getElementById('emailInvalid');

    function onlyDigits(el) { el.value = el.value.replace(/\D/g, ''); }

    nipEl.addEventListener('input', function(){
      onlyDigits(this);
      if (this.value.length > 18) this.value = this.value.slice(0,18);
      const len = this.value.length;

      // jika ada isi tapi kurang dari 18 -> tampilkan pesan length
      if (len > 0 && len < 18) {
        nipInvalid.textContent = 'NIP harus berisi 18 angka.';
        nipEl.classList.add('is-invalid');
      } else {
        // jika valid panjang 18 -> hapus invalid
        if (len === 18) {
          nipEl.classList.remove('is-invalid');
          nipEl.classList.add('is-valid');
        } else {
          // kosong -> hapus is-valid/is-invalid supaya bootstrap menampilkan saat submit
          nipEl.classList.remove('is-valid');
          nipEl.classList.remove('is-invalid');
        }
      }
    });

    hpEl.addEventListener('input', function(){
      onlyDigits(this);
      if (this.value.length > 13) this.value = this.value.slice(0,13);
    });

    // saat submit, atur pesan yang sesuai dan mencegah submit bila invalid
    form.addEventListener('submit', function(e){
      let formIsValid = true;

      // NIP check: jika kosong -> "NIP wajib diisi"; jika terisi tapi !=18 -> "NIP harus berisi 18 angka."
      const nipVal = nipEl.value.trim();
      if (nipVal === '') {
        nipInvalid.textContent = 'NIP wajib diisi.';
        nipEl.classList.add('is-invalid');
        formIsValid = false;
      } else if (nipVal.length !== 18) {
        nipInvalid.textContent = 'NIP harus berisi 18 angka.';
        nipEl.classList.add('is-invalid');
        formIsValid = false;
      } else {
        nipEl.classList.remove('is-invalid');
        nipEl.classList.add('is-valid');
      }

      // Email check: jika kosong -> Email Wajib diisi; jika format salah -> gunakan browser message
      const emailVal = emailEl.value.trim();
      if (emailVal === '') {
        emailInvalid.textContent = 'Email Wajib diisi.';
        emailEl.classList.add('is-invalid');
        formIsValid = false;
      } else {
        // gunakan HTML5 validity untuk format email
        if (emailEl.checkValidity()) {
          emailEl.classList.remove('is-invalid');
          emailEl.classList.add('is-valid');
        } else {
          // jika format salah, ubah teks menjadi pesan umum (atau biarkan default)
          emailInvalid.textContent = 'Masukkan email valid.';
          emailEl.classList.add('is-invalid');
          formIsValid = false;
        }
      }

      // Validasi input lain via constraint API
      if (!form.checkValidity()) { formIsValid = false; }

      if (!formIsValid) {
        e.preventDefault();
        e.stopPropagation();
      }

      form.classList.add('was-validated');
    });
  });
  </script>
</body>
</html>
