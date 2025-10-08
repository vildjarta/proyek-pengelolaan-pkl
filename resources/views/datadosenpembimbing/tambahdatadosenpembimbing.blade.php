<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Dosen Pembimbing | Sistem PKL JOZZ</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/tambahdatadosenpembimbing.css') }}">
</head>
<body>

{{-- HEADER --}}
@include('layout.header')

{{-- SIDEBAR --}}
@include('layout.sidebar')

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card p-4 p-md-5">

        <!-- Tombol Back -->
        <a href="{{ route('datadosenpembimbing.index') }}" class="btn-back">
          <i class="bi bi-arrow-left"></i>
        </a>

        <h3 class="text-center mb-4 page-header">Tambah Dosen Pembimbing</h3>
        <hr>

        <form action="{{ route('datadosenpembimbing.store') }}" method="POST" id="formDosenPembimbing">
          @csrf

          <!-- Dosen Section -->
          <div class="mb-4">
            <div class="mb-3">
              <label class="form-label">NIP</label>
              <input type="text" name="NIP" id="NIP" class="form-control" maxlength="18"
                     placeholder="Masukkan 18 digit NIP" value="{{ old('NIP') }}" required>
              <div id="nipError" class="text-danger mt-1" style="display:none;">NIP harus 18 angka.</div>
              @error('NIP')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Nama Dosen</label>
              <input type="text" name="nama" class="form-control" placeholder="Masukkan nama dosen"
                     value="{{ old('nama') }}" required>
              @error('nama')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="contoh@email.com"
                     value="{{ old('email') }}" required>
              @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Mahasiswa Section -->
          <div class="mb-3">
            <h5 class="fw-bold text-primary mb-3">Daftar Mahasiswa Bimbingan</h5>

            <div id="mahasiswa-list">
              <div class="mahasiswa-item mb-3">
                <div class="mb-3">
                  <label class="form-label">NIM Mahasiswa</label>
                  <input type="text" name="nim[]" class="form-control nimInput"
                         placeholder="Masukkan NIM Mahasiswa" required>
                  <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">NIM tidak ditemukan.</div>
                  <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
                    NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold"></span>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nama Mahasiswa</label>
                  <input type="text" name="nama_mahasiswa[]" class="form-control namaInput"
                         placeholder="Nama Mahasiswa Terisi Otomatis" readonly required>
                </div>
              </div>
            </div>

            <button type="button" id="add-mahasiswa" class="btn btn-primary btn-sm mt-2">
              <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </button>
          </div>

          <!-- Tombol Simpan -->
          <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4 me-2">
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
</div>

@push('scripts')
<script>
  // Validasi NIP
  const nipInput = document.getElementById("NIP");
  const nipError = document.getElementById("nipError");

  nipInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "");
    if (this.value.length > 18) this.value = this.value.slice(0, 18);
    nipError.style.display = this.value.length > 0 && this.value.length < 18 ? "block" : "none";
  });

  // Fungsi Dinamis Mahasiswa
  function setupMahasiswaItem(item, isClone = false) {
    const nimInput = item.querySelector('.nimInput');
    const nimError = item.querySelector('.nimError');
    const nimSuccess = item.querySelector('.nimSuccess');
    const namaTampil = item.querySelector('.namaTampil');
    const namaInput = item.querySelector('.namaInput');

    // Jika item hasil clone, tambahkan tombol hapus
    if (isClone && !item.querySelector('.remove-mahasiswa')) {
      const btnRemove = document.createElement('button');
      btnRemove.type = 'button';
      btnRemove.className = 'btn btn-danger btn-sm remove-mahasiswa';
      btnRemove.innerHTML = '<i class="bi bi-trash"></i> Hapus';
      item.appendChild(btnRemove);
    }

    nimInput.addEventListener('blur', function() {
      const nim = this.value.trim();
      if (!nim) {
        nimError.style.display = "none";
        nimSuccess.style.display = "none";
        namaTampil.textContent = "";
        namaInput.value = "";
        return;
      }
      fetch(`/cek-nim/${nim}`)
        .then(res => res.json())
        .then(data => {
          if (data.exists) {
            nimError.style.display = "none";
            nimSuccess.style.display = "block";
            namaTampil.textContent = data.nama_mahasiswa;
            namaInput.value = data.nama_mahasiswa;
          } else {
            nimError.style.display = "block";
            nimSuccess.style.display = "none";
            namaTampil.textContent = "";
            namaInput.value = "";
          }
        })
        .catch(() => {
          nimError.style.display = "block";
          nimSuccess.style.display = "none";
          namaTampil.textContent = "";
          namaInput.value = "";
        });
    });
  }

  // Setup item pertama
  document.querySelectorAll('.mahasiswa-item').forEach(item => setupMahasiswaItem(item));

  // Tambah mahasiswa
  document.getElementById('add-mahasiswa').addEventListener('click', function() {
    const list = document.getElementById('mahasiswa-list');
    const firstItem = list.firstElementChild;
    const newItem = firstItem.cloneNode(true);

    // Kosongkan semua field
    newItem.querySelectorAll('input').forEach(input => input.value = '');
    newItem.querySelector('.nimError').style.display = 'none';
    newItem.querySelector('.nimSuccess').style.display = 'none';
    newItem.querySelector('.namaTampil').textContent = '';

    setupMahasiswaItem(newItem, true);
    list.appendChild(newItem);
  });

  // Hapus mahasiswa
  document.getElementById('mahasiswa-list').addEventListener('click', function(e) {
    if (e.target.closest('.remove-mahasiswa')) {
      e.target.closest('.mahasiswa-item').remove();
    }
  });
</script>
@endpush

<!-- Bootstrap JS (jika belum ada di header) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
