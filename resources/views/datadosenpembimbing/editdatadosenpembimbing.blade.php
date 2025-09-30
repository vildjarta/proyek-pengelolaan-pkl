<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Dosen Pembimbing</title>
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

  <h2 class="mb-3 mt-3">Edit Dosen Pembimbing</h2>

  <form action="{{ route('datadosenpembimbing.update', $item->id_pembimbing) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">NIP</label>
      <input type="text" name="NIP" value="{{ $item->NIP }}" class="form-control" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Dosen</label>
      <input type="text" name="nama" value="{{ $item->nama }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" value="{{ $item->email }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Mahasiswa</label>
      <input type="text" name="nama_mahasiswa" value="{{ $item->nama_mahasiswa ?? '' }}" class="form-control">

    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('datadosenpembimbing.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
