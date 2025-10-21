<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Dosen Pembimbing | Sistem PKL JOZZ</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/tambahdatadosenpembimbing.css') }}">
</head>
<body>

  @include('layout.header')
  @include('layout.sidebar')

  <div class="main-wrapper" id="mainWrapper">
    <div class="content-container container-fluid px-4">
      <div class="content-card shadow-sm w-100">

        <h3 class="text-center mb-4 page-header">Tambah Dosen Pembimbing</h3>
        <hr>

        <form action="{{ route('datadosenpembimbing.store') }}" method="POST" id="formDosenPembimbing">
          @csrf

          <!-- NIP -->
          <div class="mb-3">
            <label class="form-label required">NIP</label>
            <input type="text" name="NIP" id="NIP" class="form-control" maxlength="18"
                   placeholder="Masukkan 18 digit NIP" required>
            <div id="nipError" class="text-danger mt-1" style="display:none;">NIP harus 18 angka.</div>
          </div>

          <!-- Nama Dosen -->
          <div class="mb-3">
            <label class="form-label required">Nama Dosen</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama dosen" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
          </div>

          <!-- Mahasiswa Bimbingan -->
          <div class="mb-3">
            <h5 class="fw-bold text-primary mb-3">Daftar Mahasiswa Bimbingan</h5>
            <div id="mahasiswa-list">
              <div class="mahasiswa-item mb-3 p-3 border rounded position-relative">
                <button type="button" class="btn btn-danger btn-sm remove-mahasiswa position-absolute top-0 end-0 mt-2 me-2" style="display:none;">
                  <i class="bi bi-trash"></i> Hapus
                </button>

                <div class="mb-3 mt-3">
                  <label class="form-label required">NIM Mahasiswa</label>
                  <input type="text" name="nim[]" class="form-control nimInput" placeholder="Masukkan NIM Mahasiswa" required>
                  <div class="nimError alert alert-danger py-1 px-2 mt-2 mb-0" style="display:none;">NIM tidak ditemukan.</div>
                  <div class="nimSuccess alert alert-success py-1 px-2 mt-2 mb-0" style="display:none;">
                    NIM valid. Nama mahasiswa: <span class="namaTampil fw-bold"></span>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label required">Nama Mahasiswa</label>
                  <input type="text" name="nama_mahasiswa[]" class="form-control namaInput"
                         placeholder="Nama Mahasiswa Terisi Otomatis" readonly required>
                </div>
              </div>
            </div>

            <button type="button" id="add-mahasiswa" class="btn btn-primary btn-sm mt-2">
              <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </button>
          </div>

          <!-- Tombol Simpan dan Batal -->
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
  // === Validasi NIP ===
  const nipInput = document.getElementById("NIP");
  const nipError = document.getElementById("nipError");

  nipInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, ""); // hanya angka
    if (this.value.length > 18) this.value = this.value.slice(0, 18);
    nipError.style.display = this.value.length > 0 && this.value.length < 18 ? "block" : "none";
  });

  // === Setup Event Cek NIM Mahasiswa ===
  function setupMahasiswaItem(item, isClone = false) {
    const nimInput = item.querySelector('.nimInput');
    const nimError = item.querySelector('.nimError');
    const nimSuccess = item.querySelector('.nimSuccess');
    const namaTampil = item.querySelector('.namaTampil');
    const namaInput = item.querySelector('.namaInput');
    const btnRemove = item.querySelector('.remove-mahasiswa');

    if (isClone) btnRemove.style.display = 'block';

    nimInput.addEventListener('blur', async function() {
      const nim = this.value.trim();
      if (!nim) {
        nimError.style.display = "none";
        nimSuccess.style.display = "none";
        namaTampil.textContent = "";
        namaInput.value = "";
        return;
      }

      try {
        const response = await fetch(`/cek-nim/${nim}`);
        const data = await response.json();

        if (data && data.exists && data.nama_mahasiswa) {
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
      } catch (error) {
        console.error('Gagal cek NIM:', error);
        nimError.style.display = "block";
        nimSuccess.style.display = "none";
        namaTampil.textContent = "";
        namaInput.value = "";
      }
    });
  }

  // Inisialisasi awal untuk item pertama
  document.querySelectorAll('.mahasiswa-item').forEach(item => setupMahasiswaItem(item));

  // Tombol tambah mahasiswa
  document.getElementById('add-mahasiswa').addEventListener('click', function() {
    const list = document.getElementById('mahasiswa-list');
    const firstItem = list.firstElementChild;
    const newItem = firstItem.cloneNode(true);

    // Reset semua input dan pesan
    newItem.querySelectorAll('input').forEach(input => input.value = '');
    newItem.querySelector('.nimError').style.display = 'none';
    newItem.querySelector('.nimSuccess').style.display = 'none';
    newItem.querySelector('.namaTampil').textContent = '';
    newItem.querySelector('.remove-mahasiswa').style.display = 'block';

    setupMahasiswaItem(newItem, true);
    list.appendChild(newItem);
  });

  // Tombol hapus mahasiswa
  document.getElementById('mahasiswa-list').addEventListener('click', function(e) {
    if (e.target.closest('.remove-mahasiswa')) {
      e.target.closest('.mahasiswa-item').remove();
    }
  });
</script>

</body>
</html>
