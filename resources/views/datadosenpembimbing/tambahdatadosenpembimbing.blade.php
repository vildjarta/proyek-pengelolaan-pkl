<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Dosen Pembimbing</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f9fafb;
      font-family: "Poppins", sans-serif;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }
    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="container mt-4 mb-5">
  <a href="{{ route('datadosenpembimbing.index') }}" class="text-decoration-none text-dark">
    <i class="bi bi-arrow-left-circle fs-3"></i>
  </a>

  <div class="card p-4 mt-3">
    <h3 class="mb-4 text-center fw-bold">Tambah Dosen Pembimbing</h3>

    <form action="{{ route('datadosenpembimbing.store') }}" method="POST">
      @csrf

      <!-- NIP -->
      <div class="mb-3">
        <label class="form-label">NIP</label>
        <input type="text" name="NIP" id="NIP" class="form-control" maxlength="18" value="{{ old('NIP') }}" required>
        <div id="nipError" class="text-danger mt-1" style="display:none;">NIP harus 18 angka.</div>
        @error('NIP')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <!-- Nama Dosen -->
      <div class="mb-3">
        <label class="form-label">Nama Dosen</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        @error('nama')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @error('email')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <!-- Mahasiswa Section -->
      @php $oldNim = old('nim') ?? ['']; @endphp
      <div id="mahasiswa-list">
        @foreach($oldNim as $i => $nim)
          <div class="mahasiswa-item mb-3">
            <label class="form-label">NIM Mahasiswa</label>
            <input type="text" name="nim[]" class="form-control nimInput" value="{{ $nim }}" placeholder="Masukkan NIM Mahasiswa" required>
            <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">NIM tidak ditemukan.</div>
            <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
              NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold"></span>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2 remove-mahasiswa" style="{{ $i == 0 ? 'display:none;' : 'display:inline-block;' }}"><i class="bi bi-trash"></i> Hapus</button>
          </div>
        @endforeach
      </div>
      <button type="button" id="add-mahasiswa" class="btn btn-primary btn-sm mb-3"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>

      <!-- Tombol -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">
          <i class="bi bi-check-circle"></i> Simpan
        </button>
        <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary px-4">
          <i class="bi bi-x-circle"></i> Batal
        </a>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Validasi NIP -->
<script>
  const nipInput = document.getElementById("NIP");
  const nipError = document.getElementById("nipError");

  nipInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "");
    if (this.value.length > 18) {
      this.value = this.value.slice(0, 18);
    }
    if (this.value.length > 0 && this.value.length < 18) {
      nipError.style.display = "block";
    } else {
      nipError.style.display = "none";
    }
  });
</script>

<!-- 🔍 Validasi NIM via AJAX -->
<script>
function setupMahasiswaItem(item) {
  const nimInput = item.querySelector('.nimInput');
  const nimError = item.querySelector('.nimError');
  const nimSuccess = item.querySelector('.nimSuccess');
  const namaTampil = item.querySelector('.namaTampil');

  nimInput.addEventListener('blur', function() {
    const nim = this.value.trim();
    if (nim === "") {
      nimError.style.display = "none";
      nimSuccess.style.display = "none";
      namaTampil.textContent = "";
      return;
    }
    fetch(`/cek-nim/${nim}`)
      .then(response => response.json())
      .then(data => {
        if (data.exists) {
          nimError.style.display = "none";
          nimSuccess.style.display = "block";
          namaTampil.textContent = data.nama_mahasiswa;
        } else {
          nimError.style.display = "block";
          nimSuccess.style.display = "none";
          namaTampil.textContent = "";
        }
      })
      .catch(() => {
        nimError.style.display = "block";
        nimSuccess.style.display = "none";
        namaTampil.textContent = "";
      });
  });
}

// Setup for first item
document.querySelectorAll('.mahasiswa-item').forEach(setupMahasiswaItem);

// Add Mahasiswa
document.getElementById('add-mahasiswa').addEventListener('click', function() {
  const list = document.getElementById('mahasiswa-list');
  const newItem = list.firstElementChild.cloneNode(true);

  // Reset values
  newItem.querySelector('.nimInput').value = '';
  newItem.querySelector('.nimError').style.display = 'none';
  newItem.querySelector('.nimSuccess').style.display = 'none';
  newItem.querySelector('.namaTampil').textContent = '';
  newItem.querySelector('.remove-mahasiswa').style.display = 'inline-block';

  setupMahasiswaItem(newItem);
  list.appendChild(newItem);
});

// Remove Mahasiswa
document.getElementById('mahasiswa-list').addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-mahasiswa')) {
    e.target.closest('.mahasiswa-item').remove();
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
