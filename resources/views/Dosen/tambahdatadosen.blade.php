<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Dosen - PKL JOZZ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap, Icons & Font -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  {{-- global layout --}}
  <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
  {{-- page css --}}
  <link rel="stylesheet" href="{{ asset('assets/css/tambahdatadosen.css') }}">
</head>
<body>

  @include('layout.header')
  @include('layout.sidebar')

  <div class="main-wrapper" id="mainWrapper">
    <div class="content-container container-fluid px-4">
      <div class="content-card shadow-sm w-100">

        <h2 class="text-center mb-4 page-header">Tambah Dosen</h2>
        <hr>

        <form action="{{ route('dosen.store') }}" method="POST" id="formTambahDosen" class="needs-validation" novalidate>
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

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label required">NIP</label>
              <input type="text"
                     name="nip"
                     id="nip"
                     class="form-control"
                     maxlength="18"
                     inputmode="numeric"
                     placeholder="Masukkan 18 digit NIP"
                     required
                     value="{{ old('nip') }}">
              <div id="nipInvalid" class="invalid-feedback">NIP wajib diisi.</div>
            </div>

            <div class="col-12">
              <label class="form-label required">Nama Dosen</label>
              <input type="text" name="nama" class="form-control" placeholder="Masukkan nama dosen" required value="{{ old('nama') }}">
              <div class="invalid-feedback">Nama dosen wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label required">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" required value="{{ old('email') }}">
              <div id="emailInvalid" class="invalid-feedback">Email Wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label required">Nomor HP</label>
              <input type="text"
                     name="nomor_hp"
                     id="nomor_hp"
                     class="form-control"
                     maxlength="13"
                     inputmode="numeric"
                     placeholder="08xxxxxxxxxxx"
                     required
                     value="{{ old('nomor_hp') }}">
              <div class="invalid-feedback">Nomor HP wajib diisi.</div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn action-accept px-4 me-2">
              <span class="icon-circle"><i class="bi bi-check-lg"></i></span>
              Simpan
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
    const form = document.getElementById('formTambahDosen');
    const emailEl = document.getElementById('email');
    const emailInvalid = document.getElementById('emailInvalid');

    function onlyDigits(el) {
      el.value = el.value.replace(/\D/g, '');
    }

    nipEl.addEventListener('input', function(){
      onlyDigits(this);
      if (this.value.length > 18) this.value = this.value.slice(0,18);
      const len = this.value.length;
      if (len > 0 && len < 18) {
        nipInvalid.textContent = 'NIP harus berisi 18 angka.';
        nipEl.classList.add('is-invalid');
      } else {
        nipInvalid.textContent = 'NIP wajib diisi.';
        nipEl.classList.remove('is-invalid');
        nipEl.classList.remove('is-valid');
      }
    });

    hpEl.addEventListener('input', function(){
      onlyDigits(this);
      if (this.value.length > 13) this.value = this.value.slice(0,13);
    });

    form.addEventListener('submit', function(e){
      let formIsValid = true;

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

      const emailVal = emailEl.value.trim();
      if (emailVal === '') {
        emailInvalid.textContent = 'Email Wajib diisi.';
        emailEl.classList.add('is-invalid');
        formIsValid = false;
      } else {
        if (emailEl.checkValidity()) {
          emailEl.classList.remove('is-invalid');
          emailEl.classList.add('is-valid');
        } else {
          emailInvalid.textContent = 'Masukkan email valid.';
          emailEl.classList.add('is-invalid');
          formIsValid = false;
        }
      }

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
