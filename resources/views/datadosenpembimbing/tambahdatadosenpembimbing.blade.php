<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Dosen Pembimbing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

  <!-- 🔙 Tombol Panah Kembali (tanpa teks) -->
  <a href="{{ route('datadosenpembimbing.index') }}" class="text-decoration-none text-dark">
    <i class="bi bi-arrow-left-circle fs-3"></i>
  </a>

  <h2 class="mb-3 mt-3">Tambah Dosen Pembimbing</h2>

  <form action="{{ route('datadosenpembimbing.store') }}" method="POST">
    @csrf

    <!-- NIP -->
    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="NIP" class="form-control" required>
    </div>

    <!-- Nama Dosen -->
    <div class="mb-3">
      <label class="form-label">Nama Dosen</label>
      <input type="text" name="nama" class="form-control" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <!-- Nama Mahasiswa -->
    <div class="mb-3">
      <label class="form-label">Nama Mahasiswa</label>
      <input type="text" name="nama_mahasiswa" value="{{ $item->nama_mahasiswa ?? '' }}" class="form-control">

    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
