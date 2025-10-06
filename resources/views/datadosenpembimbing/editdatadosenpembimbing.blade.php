<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Dosen Pembimbing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <a href="{{ route('datadosenpembimbing.index') }}" class="text-decoration-none text-dark">
    <i class="bi bi-arrow-left-circle fs-3"></i>
  </a>

  <h2 class="mb-3 mt-3">Edit Dosen Pembimbing</h2>

  <form action="{{ route('datadosenpembimbing.update', $item->id_pembimbing) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- NIP -->
    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="NIP" class="form-control" value="{{ old('NIP', $item->NIP) }}" required minlength="18" maxlength="18" pattern="\d{18}" title="NIP harus 18 digit angka">
    </div>

    <!-- Nama Dosen -->
    <div class="mb-3">
      <label class="form-label">Nama Dosen</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $item->email) }}" required>
    </div>

    <!-- Input NIM Mahasiswa -->
    <label class="form-label">NIM Mahasiswa</label>
    <div id="mahasiswa-list">
      @foreach($item->mahasiswa as $i => $mhs)
        <div class="mahasiswa-item mb-2">
          <div class="d-flex align-items-center">
            <input type="text" name="nim[]" class="form-control nimInput" 
                   value="{{ old('nim.' . $i, $mhs->nim) }}" 
                   placeholder="Masukkan NIM Mahasiswa" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-mahasiswa" 
                    style="{{ $i == 0 ? 'display:none;' : '' }}">
              <i class="bi bi-trash"></i>
            </button>
          </div>

          <!-- Pesan custom (AJAX) -->
          <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">
            NIM tidak ditemukan.
          </div>
          <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
            NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold">{{ $mhs->nama ?? '' }}</span>
          </div>
        </div>
      @endforeach

      @if($item->mahasiswa->isEmpty())
        <div class="mahasiswa-item mb-2">
          <div class="d-flex align-items-center">
            <input type="text" name="nim[]" class="form-control nimInput" placeholder="Masukkan NIM Mahasiswa" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-mahasiswa" style="display:none;">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">
            NIM tidak ditemukan.
          </div>
          <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
            NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold"></span>
          </div>
        </div>
      @endif
    </div>

    <button type="button" id="add-mahasiswa" class="btn btn-primary btn-sm my-2">
      <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
    </button>

    <!-- Tombol aksi -->
    <div class="mt-4 d-flex gap-2">
      <button type="submit" class="btn btn-success">Update</button>
      <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>

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

// Setup awal
document.querySelectorAll('.mahasiswa-item').forEach(setupMahasiswaItem);

// Tambah Mahasiswa
document.getElementById('add-mahasiswa').addEventListener('click', function() {
  const list = document.getElementById('mahasiswa-list');
  const newItem = list.firstElementChild.cloneNode(true);

  // Reset isi input
  newItem.querySelector('.nimInput').value = '';
  newItem.querySelector('.nimError').style.display = 'none';
  newItem.querySelector('.nimSuccess').style.display = 'none';
  newItem.querySelector('.namaTampil').textContent = '';
  newItem.querySelector('.remove-mahasiswa').style.display = 'inline-block';

  setupMahasiswaItem(newItem);
  list.appendChild(newItem);
});

// Hapus Mahasiswa
document.getElementById('mahasiswa-list').addEventListener('click', function(e) {
  let btn = e.target;
  if (btn.classList.contains('remove-mahasiswa') || btn.closest('.remove-mahasiswa')) {
    if (!btn.classList.contains('remove-mahasiswa')) {
      btn = btn.closest('.remove-mahasiswa');
    }
    btn.closest('.mahasiswa-item').remove();
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
